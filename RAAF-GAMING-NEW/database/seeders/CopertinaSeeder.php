<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CopertinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Percorso della cartella copertine (fuori dal progetto Laravel)
        $copertinePath = base_path('../Schema/copertine');
        
        // Verifica che la cartella esista
        if (!File::exists($copertinePath)) {
            $this->command->error("Cartella copertine non trovata in: {$copertinePath}");
            return;
        }

        $this->command->info("Importazione copertine in corso...");
        
        // Loop da 1 a 22
        for ($i = 1; $i <= 22; $i++) {
            // Cerca il file con diversi formati possibili
            $possibleExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filePath = null;
            
            foreach ($possibleExtensions as $ext) {
                $testPath = "{$copertinePath}/{$i}.{$ext}";
                if (File::exists($testPath)) {
                    $filePath = $testPath;
                    break;
                }
            }
            
            if (!$filePath) {
                $this->command->warn("Immagine {$i} non trovata");
                continue;
            }
            
            // Leggi il file come BLOB
            $imageData = fopen($filePath, 'rb');
            
            if ($imageData === false) {
                $this->command->error("Impossibile leggere il file: {$filePath}");
                continue;
            }
            
            // Aggiorna il prodotto con l'ID corrispondente
            $updated = DB::table('prodotto')
                ->where('codice_prodotto', $i)
                ->update(['copertina' => $imageData]);
            
            // Chiudi il file handle
            if (is_resource($imageData)) {
                fclose($imageData);
            }
            
            if ($updated)
                $this->command->info("✓ Copertina {$i} importata con successo");
            else
                $this->command->warn("Prodotto con ID {$i} non trovato nel database");
        }
        
        $this->command->info("Importazione completata!");
    }
}