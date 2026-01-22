<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;

class VoterImport extends Command
{
    protected $signature = 'voter:import {file}';
    protected $description = 'Import voters from CSV file';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info('Starting voter import...');
        
        $handle = fopen($file, 'r');
        if (!$handle) {
            $this->error("Cannot open file: {$file}");
            return 1;
        }

        // Skip header row
        $header = fgetcsv($handle);
        $this->info('Header: ' . implode(', ', array_slice($header, 0, 5)) . '...');

        DB::beginTransaction();
        
        try {
            // Clear existing data
            Voter::truncate();
            $this->info('Cleared existing voters');

            $count = 0;
            $batch = [];
            $batchSize = 500;

            while (($row = fgetcsv($handle)) !== false) {
                // Skip if row is too short or missing voter_id
                if (count($row) < 12 || empty(trim($row[11] ?? ''))) {
                    continue;
                }

                $batch[] = [
                    'serial_no' => trim($row[0] ?? ''),
                    'upazila' => trim($row[1] ?? ''),
                    'union' => trim($row[2] ?? ''),
                    'ward' => trim($row[3] ?? ''),
                    'area_code' => trim($row[4] ?? ''),
                    'area_name' => trim($row[5] ?? ''),
                    'gender' => trim($row[6] ?? ''),
                    'center_no' => trim($row[7] ?? ''),
                    'center_name' => trim($row[8] ?? ''),
                    'name' => trim($row[10] ?? ''),
                    'voter_id' => trim($row[11] ?? ''),
                    'father_name' => trim($row[12] ?? ''),
                    'mother_name' => trim($row[13] ?? ''),
                    'occupation' => trim($row[14] ?? ''),
                    'date_of_birth' => trim($row[15] ?? ''),
                    'address' => trim($row[16] ?? ''),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    Voter::insert($batch);
                    $count += count($batch);
                    $this->info("Imported {$count} records...");
                    $batch = [];
                }
            }

            // Insert remaining records
            if (!empty($batch)) {
                Voter::insert($batch);
                $count += count($batch);
            }

            fclose($handle);
            DB::commit();

            $this->info("SUCCESS! Imported {$count} voters.");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
