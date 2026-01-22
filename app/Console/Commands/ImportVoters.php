<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;

class ImportVoters extends Command
{
    protected $signature = 'voters:import {file}';
    protected $description = 'Import voters from CSV file';

    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info('Starting CSV import...');
        
        try {
            DB::beginTransaction();
            
            // Truncate existing data
            Voter::truncate();
            $this->info('Cleared existing voters');

            // Read CSV
            $handle = fopen($filePath, 'r');
            $header = fgetcsv($handle); // Skip header

            $batch = [];
            $count = 0;
            $batchSize = 500;

            while (($row = fgetcsv($handle)) !== false) {
                if (empty($row[0]) && empty($row[10])) continue;
                
                $batch[] = [
                    'serial_no' => $row[0] ?? null,
                    'upazila' => $row[1] ?? null,
                    'union' => $row[2] ?? null,
                    'ward' => $row[3] ?? null,
                    'area_code' => $row[4] ?? null,
                    'area_name' => $row[5] ?? null,
                    'gender' => $row[6] ?? null,
                    'center_no' => $row[7] ?? null,
                    'center_name' => $row[8] ?? null,
                    'voter_id' => $row[11] ?? null,
                    'name' => $row[10] ?? null,
                    'father_name' => $row[12] ?? null,
                    'mother_name' => $row[13] ?? null,
                    'occupation' => $row[14] ?? null,
                    'date_of_birth' => $row[15] ?? null,
                    'address' => $row[16] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                if (count($batch) >= $batchSize) {
                    Voter::insert($batch);
                    $count += count($batch);
                    $this->info("Imported $count records...");
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                Voter::insert($batch);
                $count += count($batch);
            }

            fclose($handle);
            DB::commit();
            
            $this->info("SUCCESS! Imported $count voters");
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
