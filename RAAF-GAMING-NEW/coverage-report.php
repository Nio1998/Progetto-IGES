<?php

/**
 * coverage-report.php
 * Genera un report HTML dal file Clover XML (merged o singolo).
 *
 * Uso: php coverage-report.php coverage-merged.xml [output-dir]
 */

if ($argc < 2) {
    echo "Uso: php coverage-report.php <clover.xml> [output-dir]\n";
    exit(1);
}

$xmlFile   = $argv[1];
$outputDir = $argv[2] ?? 'coverage-report';

if (!file_exists($xmlFile)) {
    echo "Errore: file non trovato: $xmlFile\n";
    exit(1);
}

libxml_use_internal_errors(true);
$xml = simplexml_load_file($xmlFile);
if (!$xml) {
    echo "Errore: XML non valido.\n";
    exit(1);
}

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// ─── Parsing ──────────────────────────────────────────────────────────────────

$packages = [];
$globalStatements        = 0;
$globalCoveredStatements = 0;
$globalMethods           = 0;
$globalCoveredMethods    = 0;
$globalClasses           = 0;
$globalCoveredClasses    = 0;

foreach ($xml->project->package as $package) {
    $pkgName  = (string)$package['name'];
    $pkgFiles = [];

    $pkgStatements        = 0;
    $pkgCoveredStatements = 0;
    $pkgMethods           = 0;
    $pkgCoveredMethods    = 0;

    foreach ($package->file as $file) {
        $m = null;
        foreach ($file->children() as $child) {
            if ($child->getName() === 'metrics') {
                $m = $child->attributes();
                break;
            }
        }
        if (!$m) continue;

        $statements        = (int)$m['statements'];
        $coveredStatements = (int)$m['coveredstatements'];
        $methods           = (int)$m['methods'];
        $coveredMethods    = (int)$m['coveredmethods'];
        $classes           = (int)$m['classes'];

        $fullPath  = (string)$file['name'];
        $shortName = str_replace('\\', '/', $fullPath);
        $shortName = preg_replace('#.*/app/#', 'app/', $shortName);

        $pkgFiles[] = [
            'name'              => $shortName,
            'statements'        => $statements,
            'coveredstatements' => $coveredStatements,
            'methods'           => $methods,
            'coveredmethods'    => $coveredMethods,
            'classes'           => $classes,
            'linePct'           => $statements > 0 ? round($coveredStatements / $statements * 100, 1) : 0,
            'methodPct'         => $methods    > 0 ? round($coveredMethods    / $methods    * 100, 1) : 0,
        ];

        $pkgStatements        += $statements;
        $pkgCoveredStatements += $coveredStatements;
        $pkgMethods           += $methods;
        $pkgCoveredMethods    += $coveredMethods;
    }

    if (empty($pkgFiles)) continue;

    usort($pkgFiles, fn($a, $b) => $a['linePct'] <=> $b['linePct']);

    $packages[] = [
        'name'              => $pkgName,
        'files'             => $pkgFiles,
        'statements'        => $pkgStatements,
        'coveredstatements' => $pkgCoveredStatements,
        'methods'           => $pkgMethods,
        'coveredmethods'    => $pkgCoveredMethods,
        'linePct'           => $pkgStatements > 0 ? round($pkgCoveredStatements / $pkgStatements * 100, 1) : 0,
        'methodPct'         => $pkgMethods    > 0 ? round($pkgCoveredMethods    / $pkgMethods    * 100, 1) : 0,
    ];

    $globalStatements        += $pkgStatements;
    $globalCoveredStatements += $pkgCoveredStatements;
    $globalMethods           += $pkgMethods;
    $globalCoveredMethods    += $pkgCoveredMethods;
    $globalClasses           += array_sum(array_column($pkgFiles, 'classes'));
    $globalCoveredClasses    += count(array_filter($pkgFiles, fn($f) => $f['coveredmethods'] > 0));
}

usort($packages, fn($a, $b) => $a['linePct'] <=> $b['linePct']);

$globalLinePct   = $globalStatements > 0 ? round($globalCoveredStatements / $globalStatements * 100, 1) : 0;
$globalMethodPct = $globalMethods    > 0 ? round($globalCoveredMethods    / $globalMethods    * 100, 1) : 0;
$globalClassPct  = $globalClasses    > 0 ? round($globalCoveredClasses    / $globalClasses    * 100, 1) : 0;

// ─── Helpers ──────────────────────────────────────────────────────────────────

function coverageColor(float $pct): string {
    if ($pct >= 80) return '#2ecc71';
    if ($pct >= 50) return '#f39c12';
    return '#e74c3c';
}

function coverageBadge(float $pct): string {
    $c = coverageColor($pct);
    return "<span class='badge' style='background:{$c}'>{$pct}%</span>";
}

function coverageBar(float $pct): string {
    $c = coverageColor($pct);
    return "<div class='bar-wrap'><div class='bar-fill' style='width:{$pct}%;background:{$c}'></div></div>";
}

function fmt(int $n): string {
    return number_format($n, 0, ',', '.');
}

function cardClass(float $pct): string {
    if ($pct >= 80) return 'green';
    if ($pct >= 50) return 'orange';
    return 'red';
}

// ─── HTML ─────────────────────────────────────────────────────────────────────

$generatedAt = date('d/m/Y H:i:s');
$sourceFile  = basename($xmlFile);

$html = '<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coverage Report &mdash; ' . htmlspecialchars($sourceFile) . '</title>
<style>
@import url(\'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Sora:wght@300;400;600;700&display=swap\');
:root{--bg:#0d0f14;--surface:#151820;--border:#1e2330;--text:#c8cdd8;--muted:#5a6070;--accent:#4f8ef7;--green:#2ecc71;--orange:#f39c12;--red:#e74c3c;--radius:10px}
*{box-sizing:border-box;margin:0;padding:0}
body{background:var(--bg);color:var(--text);font-family:\'Sora\',sans-serif;font-size:14px;line-height:1.6;min-height:100vh}
header{background:linear-gradient(135deg,#0d0f14 0%,#111520 100%);border-bottom:1px solid var(--border);padding:40px 48px 32px;position:relative;overflow:hidden}
header::before{content:\'\';position:absolute;top:-80px;right:-80px;width:300px;height:300px;background:radial-gradient(circle,rgba(79,142,247,0.08) 0%,transparent 70%);pointer-events:none}
header h1{font-size:26px;font-weight:700;color:#fff;letter-spacing:-.5px;margin-bottom:4px}
header h1 span{color:var(--accent)}
header p{color:var(--muted);font-size:13px;font-family:\'JetBrains Mono\',monospace}
.summary{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;padding:32px 48px;max-width:900px}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:20px 24px;position:relative;overflow:hidden;transition:border-color .2s}
.card:hover{border-color:var(--accent)}
.card::after{content:\'\';position:absolute;bottom:0;left:0;right:0;height:3px}
.card.green::after{background:var(--green)}
.card.orange::after{background:var(--orange)}
.card.red::after{background:var(--red)}
.card-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:8px}
.card-pct{font-size:36px;font-weight:700;font-family:\'JetBrains Mono\',monospace;color:#fff;line-height:1;margin-bottom:10px}
.card-detail{font-size:12px;color:var(--muted);font-family:\'JetBrains Mono\',monospace}
.card-bar{margin-top:12px;height:4px;background:var(--border);border-radius:2px;overflow:hidden}
.card-bar-fill{height:100%;border-radius:2px}
main{padding:0 48px 60px}
.section-title{font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted);margin:32px 0 16px;display:flex;align-items:center;gap:10px}
.section-title::after{content:\'\';flex:1;height:1px;background:var(--border)}
.pkg{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:12px;overflow:hidden}
.pkg-header{display:grid;grid-template-columns:1fr 180px 180px 100px;align-items:center;padding:14px 20px;cursor:pointer;background:rgba(255,255,255,0.02);border-bottom:1px solid var(--border);user-select:none;transition:background .15s}
.pkg-header:hover{background:rgba(255,255,255,0.04)}
.pkg-name{font-family:\'JetBrains Mono\',monospace;font-size:12px;color:var(--accent);font-weight:600}
.pkg-arrow{color:var(--muted);font-size:10px;text-align:right;transition:transform .2s}
.pkg.open .pkg-arrow{transform:rotate(180deg)}
.pkg-files{display:none}
.pkg.open .pkg-files{display:block}
table{width:100%;border-collapse:collapse}
th{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--muted);padding:10px 20px;text-align:left;border-bottom:1px solid var(--border);background:rgba(0,0,0,0.2)}
th.right,td.right{text-align:right}
tr.file-row{transition:background .1s}
tr.file-row:hover{background:rgba(255,255,255,0.03)}
td{padding:11px 20px;border-bottom:1px solid rgba(30,35,48,0.5);font-size:13px}
tr:last-child td{border-bottom:none}
.file-name{font-family:\'JetBrains Mono\',monospace;font-size:12px;color:var(--text)}
.file-name .path{color:var(--muted)}
.bar-wrap{width:100%;height:6px;background:var(--border);border-radius:3px;overflow:hidden}
.bar-fill{height:100%;border-radius:3px}
.badge{display:inline-block;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;font-family:\'JetBrains Mono\',monospace;color:#fff}
.num{font-family:\'JetBrains Mono\',monospace;font-size:12px;color:var(--muted)}
footer{text-align:center;padding:24px;border-top:1px solid var(--border);color:var(--muted);font-size:12px;font-family:\'JetBrains Mono\',monospace}
</style>
</head>
<body>

<header>
  <h1>Coverage Report <span>//</span> ' . htmlspecialchars($sourceFile) . '</h1>
  <p>Generato il ' . $generatedAt . '</p>
</header>

<div class="summary">
  <div class="card ' . cardClass($globalLinePct) . '">
    <div class="card-label">Lines</div>
    <div class="card-pct">' . $globalLinePct . '%</div>
    <div class="card-detail">' . fmt($globalCoveredStatements) . ' / ' . fmt($globalStatements) . '</div>
    <div class="card-bar"><div class="card-bar-fill" style="width:' . $globalLinePct . '%;background:' . coverageColor($globalLinePct) . '"></div></div>
  </div>
  <div class="card ' . cardClass($globalMethodPct) . '">
    <div class="card-label">Methods</div>
    <div class="card-pct">' . $globalMethodPct . '%</div>
    <div class="card-detail">' . fmt($globalCoveredMethods) . ' / ' . fmt($globalMethods) . '</div>
    <div class="card-bar"><div class="card-bar-fill" style="width:' . $globalMethodPct . '%;background:' . coverageColor($globalMethodPct) . '"></div></div>
  </div>
  <div class="card ' . cardClass($globalClassPct) . '">
    <div class="card-label">Classes</div>
    <div class="card-pct">' . $globalClassPct . '%</div>
    <div class="card-detail">' . fmt($globalCoveredClasses) . ' / ' . fmt($globalClasses) . '</div>
    <div class="card-bar"><div class="card-bar-fill" style="width:' . $globalClassPct . '%;background:' . coverageColor($globalClassPct) . '"></div></div>
  </div>
</div>

<main>
  <div class="section-title">Dettaglio per package</div>
';

foreach ($packages as $pkg) {
    $html .= '
  <div class="pkg">
    <div class="pkg-header" onclick="toggle(this.parentElement)">
      <div>
        <div class="pkg-name">' . htmlspecialchars($pkg['name']) . '</div>
        <div style="font-size:11px;color:var(--muted);margin-top:3px">' . count($pkg['files']) . ' file</div>
      </div>
      <div style="padding-right:16px">
        ' . coverageBar($pkg['linePct']) . '
        <div style="font-size:11px;color:var(--muted);margin-top:4px;font-family:\'JetBrains Mono\',monospace">Lines ' . $pkg['linePct'] . '%</div>
      </div>
      <div style="padding-right:16px">
        ' . coverageBar($pkg['methodPct']) . '
        <div style="font-size:11px;color:var(--muted);margin-top:4px;font-family:\'JetBrains Mono\',monospace">Methods ' . $pkg['methodPct'] . '%</div>
      </div>
      <div style="text-align:right">
        ' . coverageBadge($pkg['linePct']) . '
        <div class="pkg-arrow">&#9660;</div>
      </div>
    </div>
    <div class="pkg-files">
      <table>
        <thead>
          <tr>
            <th>File</th>
            <th>Lines</th>
            <th>Methods</th>
            <th class="right">Covered</th>
            <th class="right">Total</th>
          </tr>
        </thead>
        <tbody>';

    foreach ($pkg['files'] as $f) {
        $parts = explode('/', $f['name']);
        $fname = array_pop($parts);
        $fpath = implode('/', $parts) . '/';

        $html .= '
          <tr class="file-row">
            <td>
              <div class="file-name">
                <span class="path">' . htmlspecialchars($fpath) . '</span>' . htmlspecialchars($fname) . '
              </div>
            </td>
            <td style="width:160px">
              ' . coverageBar($f['linePct']) . '
              <div style="font-size:11px;color:var(--muted);margin-top:3px;font-family:\'JetBrains Mono\',monospace">' . $f['linePct'] . '%</div>
            </td>
            <td style="width:160px">
              ' . coverageBar($f['methodPct']) . '
              <div style="font-size:11px;color:var(--muted);margin-top:3px;font-family:\'JetBrains Mono\',monospace">' . $f['methodPct'] . '%</div>
            </td>
            <td class="right num">' . fmt($f['coveredstatements']) . '</td>
            <td class="right num">' . fmt($f['statements']) . '</td>
          </tr>';
    }

    $html .= '
        </tbody>
      </table>
    </div>
  </div>';
}

$html .= '
</main>

<footer>
  coverage-report.php &mdash; generato da ' . htmlspecialchars($sourceFile) . ' &mdash; ' . $generatedAt . '
</footer>

<script>
function toggle(el) { el.classList.toggle(\'open\'); }
document.querySelectorAll(\'.pkg\').forEach(p => p.classList.add(\'open\'));
</script>
</body>
</html>';

$outFile = $outputDir . '/index.html';
file_put_contents($outFile, $html);

echo "Report HTML generato: {$outFile}\n";
echo "Aprilo nel browser con: start {$outFile}\n\n";