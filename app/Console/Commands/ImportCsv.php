<?php
/**
 * Laravel Artisan Command for CSV Import
 * Usage: php artisan import:csv
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voter;
use App\Helpers\BengaliTransliterator;
use Illuminate\Support\Facades\DB;

class ImportCsv extends Command
{
    protected $signature = 'import:csv {file?}';
    protected $description = 'Import voters from CSV file';

    public function handle()
    {
        $csvFile = $this->argument('file') ?? base_path('Voter_Data.csv');
        
        if (!file_exists($csvFile)) {
            $this->error("CSV file not found: $csvFile");
            return 1;
        }

        $this->info("🔗 Starting import from: $csvFile");

        $handle = fopen($csvFile, 'r');
        if (!$handle) {
            $this->error("Cannot open CSV file");
            return 1;
        }

        // Skip header
        fgetcsv($handle);

        // Get existing voter IDs
        $this->info("📊 Loading existing voter IDs...");
        $existingVoters = Voter::pluck('id', 'voter_id')->toArray();
        $this->info("📊 Found " . count($existingVoters) . " existing voters");

        DB::beginTransaction();

        $newCount = 0;
        $updateCount = 0;
        $skipCount = 0;
        $lineNum = 1;
        $insertBatch = [];
        $batchSize = 500;

        $this->info("🚀 Processing CSV...");

        while (($row = fgetcsv($handle)) !== false) {
            $lineNum++;

            if (count($row) < 12) {
                $skipCount++;
                continue;
            }

            $serialNo = trim($row[0] ?? '');
            $upazila = trim($row[1] ?? '');
            $union = trim($row[2] ?? '');
            $ward = trim($row[3] ?? '');
            $areaCode = trim($row[4] ?? '');
            $areaName = trim($row[5] ?? '');
            $gender = trim($row[6] ?? '');
            $centerNo = trim($row[7] ?? '');
            $centerName = trim($row[8] ?? '');
            $name = trim($row[10] ?? '');
            $voterId = trim($row[11] ?? '');
            $fatherName = trim($row[12] ?? '');
            $motherName = trim($row[13] ?? '');
            $occupation = trim($row[14] ?? '');
            $dob = trim($row[15] ?? '');
            $address = trim($row[16] ?? '');

            if (empty($serialNo) && empty($name)) {
                $skipCount++;
                continue;
            }

            $voterData = [
                'serial_no' => $serialNo,
                'upazila' => $upazila,
                'union' => $union,
                'ward' => $ward,
                'area_code' => $areaCode,
                'area_name' => $areaName,
                'gender' => $gender,
                'center_no' => $centerNo,
                'center_name' => $centerName,
                'name' => $name,
                'name_en' => BengaliTransliterator::transliterate($name),
                'voter_id' => $voterId,
                'father_name' => $fatherName,
                'father_name_en' => BengaliTransliterator::transliterate($fatherName),
                'mother_name' => $motherName,
                'mother_name_en' => BengaliTransliterator::transliterate($motherName),
                'occupation' => $occupation,
                'profession_en' => BengaliTransliterator::transliterate($occupation),
                'date_of_birth' => $dob,
                'address' => $address,
                'address_en' => BengaliTransliterator::transliterate($address),
                'updated_at' => now(),
            ];

            if ($voterId && isset($existingVoters[$voterId])) {
                Voter::where('id', $existingVoters[$voterId])->update($voterData);
                $updateCount++;
            } else {
                $voterData['created_at'] = now();
                $insertBatch[] = $voterData;
                $newCount++;

                if (count($insertBatch) >= $batchSize) {
                    Voter::insert($insertBatch);
                    $insertBatch = [];
                    gc_collect_cycles();
                }
            }

            if ($lineNum % 1000 == 0) {
                $this->info("📝 Processed $lineNum rows (New: $newCount, Updated: $updateCount)");
            }
        }

        // Insert remaining
        if (!empty($insertBatch)) {
            Voter::insert($insertBatch);
        }

        DB::commit();
        fclose($handle);

        $this->info("\n✅ Import Complete!");
        $this->info("📊 New records: $newCount");
        $this->info("📊 Updated records: $updateCount");
        $this->info("📊 Skipped: $skipCount");
        $this->info("📊 Total in database: " . Voter::count());

        return 0;
    }
}
