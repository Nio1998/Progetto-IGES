<?php

/**
 * merge-coverage.php
 * Unisce due file Clover XML (es. coverage di sistema + coverage di unità)
 * Logica: per ogni riga, prende il count massimo tra i due file.
 * Una riga è "coperta" se count > 0 in almeno uno dei due XML.
 *
 * Uso: php merge-coverage.php coverage-sistema.xml coverage-unita.xml coverage-merged.xml
 */

// ─── Argomenti ────────────────────────────────────────────────────────────────

if ($argc < 3) {
    echo "Uso: php merge-coverage.php <file1.xml> <file2.xml> [output.xml]\n";
    exit(1);
}

$fileA  = $argv[1];
$fileB  = $argv[2];
$output = $argv[3] ?? 'coverage-merged.xml';

foreach ([$fileA, $fileB] as $f) {
    if (!file_exists($f)) {
        echo "Errore: file non trovato: $f\n";
        exit(1);
    }
}

// ─── Caricamento XML ──────────────────────────────────────────────────────────

libxml_use_internal_errors(true);

$xmlA = simplexml_load_file($fileA);
$xmlB = simplexml_load_file($fileB);

if (!$xmlA || !$xmlB) {
    foreach (libxml_get_errors() as $err) {
        echo "Errore XML: " . $err->message;
    }
    exit(1);
}

// ─── Indicizza il file B per velocizzare la ricerca ───────────────────────────
// Struttura: $indexB[fileName][lineNum] = count

function indexXml(SimpleXMLElement $xml): array {
    $index = [];
    foreach ($xml->project->package as $package) {
        foreach ($package->file as $file) {
            $fileName = (string)$file['name'];
            $index[$fileName] = [];
            foreach ($file->line as $line) {
                $num   = (int)$line['num'];
                $count = (int)$line['count'];
                $index[$fileName][$num] = $count;
            }
        }
    }
    return $index;
}

echo "Indicizzazione $fileA...\n";
$indexA = indexXml($xmlA);
echo "Indicizzazione $fileB...\n";
$indexB = indexXml($xmlB);

// ─── Costruzione XML merged ───────────────────────────────────────────────────

$timestamp = time();
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$root = $dom->createElement('coverage');
$root->setAttribute('generated', (string)$timestamp);
$dom->appendChild($root);

$project = $dom->createElement('project');
$project->setAttribute('timestamp', (string)$timestamp);
$root->appendChild($project);

// Totali globali da ricalcolare
$globalStatements        = 0;
$globalCoveredStatements = 0;
$globalMethods           = 0;
$globalCoveredMethods    = 0;
$globalClasses           = 0;
$globalCoveredClasses    = 0;
$globalFiles             = 0;
$globalLoc               = 0;
$globalNcloc             = 0;

// Itera i package dell'XML A (fa da base)
foreach ($xmlA->project->package as $packageA) {
    $packageName = (string)$packageA['name'];

    $packageEl = $dom->createElement('package');
    $packageEl->setAttribute('name', $packageName);
    $project->appendChild($packageEl);

    foreach ($packageA->file as $fileA_node) {
        $fileName = (string)$fileA_node['name'];
        $globalFiles++;

        $fileEl = $dom->createElement('file');
        $fileEl->setAttribute('name', $fileName);
        $packageEl->appendChild($fileEl);

        // ── Aggiungi le classi (metadati, non cambiano) ──────────────────────
        foreach ($fileA_node->class as $classA) {
            $classEl = $dom->createElement('class');
            $classEl->setAttribute('name',      (string)$classA['name']);
            $classEl->setAttribute('namespace', (string)$classA['namespace']);
            // Il tag <metrics> dentro <class> lo ricalcoliamo dopo
            $fileEl->appendChild($classEl);
        }

        // ── Merge delle righe ────────────────────────────────────────────────
        $fileStatements        = 0;
        $fileCoveredStatements = 0;
        $fileMethods           = 0;
        $fileCoveredMethods    = 0;

        foreach ($fileA_node->line as $lineA) {
            $num   = (int)$lineA['num'];
            $type  = (string)$lineA['type'];
            $countA = (int)$lineA['count'];
            $countB = $indexB[$fileName][$num] ?? 0;
            $merged = max($countA, $countB);

            $lineEl = $dom->createElement('line');
            $lineEl->setAttribute('num',   (string)$num);
            $lineEl->setAttribute('type',  $type);

            // Copia attributi aggiuntivi se presenti (name, visibility, complexity, crap)
            foreach (['name','visibility','complexity','crap'] as $attr) {
                if (isset($lineA[$attr])) {
                    $lineEl->setAttribute($attr, (string)$lineA[$attr]);
                }
            }

            $lineEl->setAttribute('count', (string)$merged);
            $fileEl->appendChild($lineEl);

            if ($type === 'stmt') {
                $fileStatements++;
                if ($merged > 0) $fileCoveredStatements++;
            } elseif ($type === 'method') {
                $fileMethods++;
                if ($merged > 0) $fileCoveredMethods++;
            }
        }

        // Aggiungi eventuali righe presenti SOLO in B (non in A)
        if (isset($indexB[$fileName])) {
            $linesInA = [];
            foreach ($fileA_node->line as $l) {
                $linesInA[(int)$l['num']] = true;
            }
            foreach ($indexB[$fileName] as $num => $countB) {
                if (!isset($linesInA[$num]) && $countB > 0) {
                    $lineEl = $dom->createElement('line');
                    $lineEl->setAttribute('num',   (string)$num);
                    $lineEl->setAttribute('type',  'stmt');
                    $lineEl->setAttribute('count', (string)$countB);
                    $fileEl->appendChild($lineEl);
                    $fileStatements++;
                    $fileCoveredStatements++;
                }
            }
        }

        // ── Calcolo classi coperte ───────────────────────────────────────────
        $fileClasses        = 0;
        $fileCoveredClasses = 0;
        foreach ($fileA_node->class as $classA) {
            $fileClasses++;
            // Una classe è coperta se almeno un suo metodo è coperto
            if ($fileCoveredMethods > 0) $fileCoveredClasses++;
        }

        // ── Metrics a livello file ───────────────────────────────────────────
        $fileLoc   = (int)($fileA_node->metrics['loc']   ?? 0);
        $fileNcloc = (int)($fileA_node->metrics['ncloc'] ?? 0);

        $metricsEl = $dom->createElement('metrics');
        $metricsEl->setAttribute('loc',                (string)$fileLoc);
        $metricsEl->setAttribute('ncloc',              (string)$fileNcloc);
        $metricsEl->setAttribute('classes',            (string)$fileClasses);
        $metricsEl->setAttribute('methods',            (string)$fileMethods);
        $metricsEl->setAttribute('coveredmethods',     (string)$fileCoveredMethods);
        $metricsEl->setAttribute('conditionals',       '0');
        $metricsEl->setAttribute('coveredconditionals','0');
        $metricsEl->setAttribute('statements',         (string)$fileStatements);
        $metricsEl->setAttribute('coveredstatements',  (string)$fileCoveredStatements);
        $metricsEl->setAttribute('elements',           (string)($fileStatements + $fileMethods));
        $metricsEl->setAttribute('coveredelements',    (string)($fileCoveredStatements + $fileCoveredMethods));
        $fileEl->appendChild($metricsEl);

        // ── Accumulo globale ─────────────────────────────────────────────────
        $globalStatements        += $fileStatements;
        $globalCoveredStatements += $fileCoveredStatements;
        $globalMethods           += $fileMethods;
        $globalCoveredMethods    += $fileCoveredMethods;
        $globalClasses           += $fileClasses;
        $globalCoveredClasses    += $fileCoveredClasses;
        $globalLoc               += $fileLoc;
        $globalNcloc             += $fileNcloc;
    }
}

// ─── Metrics globali ──────────────────────────────────────────────────────────

$globalMetrics = $dom->createElement('metrics');
$globalMetrics->setAttribute('files',               (string)$globalFiles);
$globalMetrics->setAttribute('loc',                 (string)$globalLoc);
$globalMetrics->setAttribute('ncloc',               (string)$globalNcloc);
$globalMetrics->setAttribute('classes',             (string)$globalClasses);
$globalMetrics->setAttribute('methods',             (string)$globalMethods);
$globalMetrics->setAttribute('coveredmethods',      (string)$globalCoveredMethods);
$globalMetrics->setAttribute('conditionals',        '0');
$globalMetrics->setAttribute('coveredconditionals', '0');
$globalMetrics->setAttribute('statements',          (string)$globalStatements);
$globalMetrics->setAttribute('coveredstatements',   (string)$globalCoveredStatements);
$globalMetrics->setAttribute('elements',            (string)($globalStatements + $globalMethods));
$globalMetrics->setAttribute('coveredelements',     (string)($globalCoveredStatements + $globalCoveredMethods));
$project->appendChild($globalMetrics);

// ─── Salvataggio ─────────────────────────────────────────────────────────────

$dom->save($output);
echo "File merged salvato: $output\n\n";

// ─── Stampa statistiche ───────────────────────────────────────────────────────

function pct(int $covered, int $total): string {
    if ($total === 0) return '  n/a  ';
    return number_format($covered / $total * 100, 1, ',', '.') . '%';
}

function bar(int $covered, int $total, int $width = 20): string {
    if ($total === 0) return str_repeat(' ', $width);
    $green = (int)round($covered / $total * $width);
    $red   = $width - $green;
    return "\033[42m" . str_repeat(' ', $green) . "\033[0m"
         . "\033[41m" . str_repeat(' ', $red)   . "\033[0m";
}

function printTable(string $label, array $rows): void {
    $colW = [12, 28, 12, 12, 12];
    echo "\n  === $label ===\n\n";
    printf("%-{$colW[0]}s%-{$colW[1]}s%{$colW[2]}s%{$colW[3]}s%{$colW[4]}s\n",
        'Counter', 'Coverage', 'Covered', 'Missed', 'Total');
    echo str_repeat('-', array_sum($colW)) . "\n";
    foreach ($rows as [$name, $covered, $total]) {
        $missed = $total - $covered;
        printf("%-{$colW[0]}s", $name);
        echo bar($covered, $total) . ' ';
        printf("%-7s", pct($covered, $total));
        printf("%{$colW[2]}s%{$colW[3]}s%{$colW[4]}s\n",
            number_format($covered), number_format($missed), number_format($total));
    }
    echo str_repeat('-', array_sum($colW)) . "\n";
}

// Leggi i totali dai file originali
function readTotals(SimpleXMLElement $xml): array {
    foreach ($xml->project->children() as $child) {
        if ($child->getName() === 'metrics') {
            $m = $child->attributes();
            return [
                'statements'        => (int)$m['statements'],
                'coveredstatements' => (int)$m['coveredstatements'],
                'methods'           => (int)$m['methods'],
                'coveredmethods'    => (int)$m['coveredmethods'],
            ];
        }
    }
    return ['statements'=>0,'coveredstatements'=>0,'methods'=>0,'coveredmethods'=>0];
}

$totA = readTotals($xmlA);
$totB = readTotals($xmlB);

printTable(basename($fileA), [
    ['Lines',   $totA['coveredstatements'], $totA['statements']],
    ['Methods', $totA['coveredmethods'],    $totA['methods']],
]);

printTable(basename($fileB), [
    ['Lines',   $totB['coveredstatements'], $totB['statements']],
    ['Methods', $totB['coveredmethods'],    $totB['methods']],
]);

printTable('MERGED (' . basename($output) . ')', [
    ['Lines',   $globalCoveredStatements, $globalStatements],
    ['Methods', $globalCoveredMethods,    $globalMethods],
]);

echo "\n";