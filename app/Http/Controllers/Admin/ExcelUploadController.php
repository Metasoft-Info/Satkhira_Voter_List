<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use App\Helpers\BengaliTransliterator;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use Illuminate\Support\Facades\DB;

/**
 * Chunk Read Filter - reads only specific rows to save memory
 */
class ChunkReadFilter implements IReadFilter
{
    private $startRow;
    private $endRow;

    public function __construct($startRow, $endRow)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    public function readCell($columnAddress, $row, $worksheetName = ''): bool
    {
        if ($row === 1 || ($row >= $this->startRow && $row <= $this->endRow)) {
            return true;
        }
        return false;
    }
}

class ExcelUploadController extends Controller
{
    public function index()
    {
        $lastUpload = Voter::latest('updated_at')->first();
        $stats = [
            'total' => Voter::count(),
            'lastUpdate' => $lastUpload ? $lastUpload->updated_at->format('d M Y, h:i A') : 'কোন ডেটা নেই',
        ];
        return view('admin.upload', compact('lastUpload', 'stats'));
    }

    /**
     * Upload handler - supports both CSV and XLSX
     * CSV is recommended for large files (5 lakh+ records)
     */
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:500000',
            'upload_mode' => 'required|in:smart,replace',
        ]);

        // Increase execution time and memory for large files
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        
        // Disable output buffering
        if (ob_get_level()) {
            ob_end_clean();
        }

        $mode = $request->input('upload_mode', 'smart');
        $file = $request->file('excel_file');
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            // Route to appropriate handler based on file type
            if (in_array($extension, ['csv', 'txt'])) {
                return $this->uploadCsv($file, $mode);
            } else {
                return $this->uploadExcel($file, $mode);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.upload')
                ->with('error', 'আপলোড ব্যর্থ: ' . $e->getMessage());
        }
    }

    /**
     * CSV Upload - Super fast for large files (5 lakh+ records)
     * Reads line by line without loading entire file into memory
     */
    private function uploadCsv($file, $mode)
    {
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            throw new \Exception('ফাইল খুলতে পারছি না');
        }

        // Skip header row
        fgetcsv($handle);

        DB::beginTransaction();

        if ($mode === 'replace') {
            Voter::truncate();
        }

        $existingVoters = [];
        if ($mode === 'smart') {
            $existingVoters = Voter::pluck('id', 'voter_id')->toArray();
        }

        $newCount = 0;
        $updateCount = 0;
        $skipCount = 0;
        $batchSize = 1000;
        $insertBatch = [];
        $updateBatch = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 12 || (empty($row[0]) && empty($row[10]))) {
                $skipCount++;
                continue;
            }

            $voterId = $row[11] ?? null;
            $nameBn = $row[10] ?? null;
            $fatherNameBn = $row[12] ?? null;
            $motherNameBn = $row[13] ?? null;
            $occupationBn = $row[14] ?? null;
            $addressBn = $row[16] ?? null;

            $voterData = [
                'serial_no' => $row[0] ?? null,
                'upazila' => $row[1] ?? null,
                'union' => $row[2] ?? null,
                'ward' => $row[3] ?? null,
                'area_code' => $row[4] ?? null,
                'area_name' => $row[5] ?? null,
                'gender' => $row[6] ?? null,
                'center_no' => $row[7] ?? null,
                'center_name' => $row[8] ?? null,
                'name' => $nameBn,
                'name_en' => BengaliTransliterator::transliterate($nameBn),
                'voter_id' => $voterId,
                'father_name' => $fatherNameBn,
                'father_name_en' => BengaliTransliterator::transliterate($fatherNameBn),
                'mother_name' => $motherNameBn,
                'mother_name_en' => BengaliTransliterator::transliterate($motherNameBn),
                'occupation' => $occupationBn,
                'profession_en' => BengaliTransliterator::transliterate($occupationBn),
                'date_of_birth' => $row[15] ?? null,
                'address' => $addressBn,
                'address_en' => BengaliTransliterator::transliterate($addressBn),
                'updated_at' => now(),
            ];

            if ($mode === 'smart' && $voterId && isset($existingVoters[$voterId])) {
                $updateBatch[] = array_merge($voterData, ['id' => $existingVoters[$voterId]]);
                $updateCount++;
                
                if (count($updateBatch) >= $batchSize) {
                    $this->batchUpdate($updateBatch);
                    $updateBatch = [];
                    gc_collect_cycles();
                }
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
        }

        // Insert remaining batches
        if (!empty($insertBatch)) {
            Voter::insert($insertBatch);
        }
        if (!empty($updateBatch)) {
            $this->batchUpdate($updateBatch);
        }

        fclose($handle);
        DB::commit();

        $message = $mode === 'smart'
            ? "CSV আপলোড সফল! নতুন: {$newCount}, আপডেট: {$updateCount}, স্কিপ: {$skipCount}"
            : "CSV আপলোড সফল! মোট: " . ($newCount + $updateCount) . " রেকর্ড";

        return redirect()->route('admin.upload')->with('success', $message);
    }

    /**
     * Excel Upload - For smaller files (under 50k records)
     * Uses chunk reading to manage memory
     */
    private function uploadExcel($file, $mode)
    {
        $tempPath = $file->getRealPath();
            
        // Use chunk reader filter to read in smaller pieces
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        
        // First, get total rows count only
        $spreadsheetInfo = $reader->listWorksheetInfo($tempPath);
        $totalRows = $spreadsheetInfo[0]['totalRows'] ?? 0;
        
        if ($totalRows <= 1) {
            throw new \Exception('এক্সেল ফাইলে কোন ডেটা নেই!');
        }

        // Warn if file is too large for Excel processing
        if ($totalRows > 50000) {
            throw new \Exception("এক্সেল ফাইলে {$totalRows} সারি আছে। ৫০,০০০+ রেকর্ডের জন্য CSV ফাইল ব্যবহার করুন (Excel → Save As → CSV UTF-8)।");
        }

        DB::beginTransaction();

        // If replace mode, truncate first
        if ($mode === 'replace') {
            Voter::truncate();
        }

        // Get existing voter IDs for comparison (only in smart mode)
        $existingVoters = [];
        if ($mode === 'smart') {
            $existingVoters = Voter::pluck('id', 'voter_id')->toArray();
        }

        $newCount = 0;
        $updateCount = 0;
        $skipCount = 0;
        $chunkSize = 500;
        
        // Process in chunks
        for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
            $endRow = min($startRow + $chunkSize - 1, $totalRows);
            
            // Create chunk filter
            $chunkFilter = new ChunkReadFilter($startRow, $endRow);
            $reader->setReadFilter($chunkFilter);
            
            // Load only this chunk
            $spreadsheet = $reader->load($tempPath);
            $sheet = $spreadsheet->getActiveSheet();
            
            $insertBatch = [];
            $updateBatch = [];
            
            for ($row = $startRow; $row <= $endRow; $row++) {
                $rowData = [];
                for ($col = 1; $col <= 17; $col++) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row);
                    $rowData[] = $cell ? $cell->getValue() : null;
                }

                // Skip empty rows
                if (empty($rowData[0]) && empty($rowData[10])) {
                    $skipCount++;
                    continue;
                }

                $voterId = $rowData[11] ?? null;
                
                // Get Bengali values
                $nameBn = $rowData[10] ?? null;
                $fatherNameBn = $rowData[12] ?? null;
                $motherNameBn = $rowData[13] ?? null;
                $occupationBn = $rowData[14] ?? null;
                $addressBn = $rowData[16] ?? null;
                
                $voterData = [
                    'serial_no' => $rowData[0] ?? null,
                    'upazila' => $rowData[1] ?? null,
                    'union' => $rowData[2] ?? null,
                    'ward' => $rowData[3] ?? null,
                    'area_code' => $rowData[4] ?? null,
                    'area_name' => $rowData[5] ?? null,
                    'gender' => $rowData[6] ?? null,
                    'center_no' => $rowData[7] ?? null,
                    'center_name' => $rowData[8] ?? null,
                    'name' => $nameBn,
                    'name_en' => BengaliTransliterator::transliterate($nameBn),
                    'voter_id' => $voterId,
                    'father_name' => $fatherNameBn,
                    'father_name_en' => BengaliTransliterator::transliterate($fatherNameBn),
                    'mother_name' => $motherNameBn,
                    'mother_name_en' => BengaliTransliterator::transliterate($motherNameBn),
                    'occupation' => $occupationBn,
                    'profession_en' => BengaliTransliterator::transliterate($occupationBn),
                    'date_of_birth' => $rowData[15] ?? null,
                    'address' => $addressBn,
                    'address_en' => BengaliTransliterator::transliterate($addressBn),
                    'updated_at' => now(),
                ];

                if ($mode === 'smart' && $voterId && isset($existingVoters[$voterId])) {
                    $updateBatch[] = array_merge($voterData, ['id' => $existingVoters[$voterId]]);
                    $updateCount++;
                } else {
                    $voterData['created_at'] = now();
                    $insertBatch[] = $voterData;
                    $newCount++;
                }
            }
            
            // Insert/Update this chunk
            if (!empty($insertBatch)) {
                Voter::insert($insertBatch);
            }
            if (!empty($updateBatch)) {
                $this->batchUpdate($updateBatch);
            }
            
            // Free memory
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet, $sheet, $insertBatch, $updateBatch);
            gc_collect_cycles();
        }

        DB::commit();

        $message = $mode === 'smart' 
            ? "এক্সেল আপলোড সফল! নতুন: {$newCount}, আপডেট: {$updateCount}, স্কিপ: {$skipCount}"
            : "এক্সেল আপলোড সফল! মোট: " . ($newCount + $updateCount) . " রেকর্ড";

        return redirect()->route('admin.upload')->with('success', $message);
    }

    /**
     * Batch update voters using CASE WHEN
     */
    private function batchUpdate(array $voters)
    {
        if (empty($voters)) return;

        foreach ($voters as $voter) {
            Voter::where('id', $voter['id'])->update($voter);
        }
    }

    public function resetVoters()
    {
        try {
            DB::beginTransaction();
            
            $count = Voter::count();
            Voter::truncate();
            
            DB::commit();

            return redirect()->route('admin.upload')
                ->with('success', "{$count} টি ভোটার ডেটা সফলভাবে মুছে ফেলা হয়েছে!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.upload')
                ->with('error', 'ডেটা মুছতে ব্যর্থ: ' . $e->getMessage());
        }
    }

    /**
     * Transliterate Bengali names to English for all voters
     */
    public function transliterate()
    {
        set_time_limit(600); // 10 minutes
        ini_set('memory_limit', '2048M');

        try {
            $total = Voter::count();
            $processed = 0;
            $batchSize = 500;

            Voter::query()
                ->select(['id', 'name', 'father_name', 'mother_name', 'occupation', 'address'])
                ->chunkById($batchSize, function ($voters) use (&$processed) {
                    foreach ($voters as $voter) {
                        $updateData = [];
                        
                        if ($voter->name) {
                            $updateData['name_en'] = BengaliTransliterator::transliterate($voter->name);
                        }
                        
                        if ($voter->father_name) {
                            $updateData['father_name_en'] = BengaliTransliterator::transliterate($voter->father_name);
                        }
                        
                        if ($voter->mother_name) {
                            $updateData['mother_name_en'] = BengaliTransliterator::transliterate($voter->mother_name);
                        }
                        
                        if ($voter->occupation) {
                            $updateData['profession_en'] = BengaliTransliterator::transliterate($voter->occupation);
                        }
                        
                        if ($voter->address) {
                            $updateData['address_en'] = BengaliTransliterator::transliterate($voter->address);
                        }
                        
                        if (!empty($updateData)) {
                            Voter::where('id', $voter->id)->update($updateData);
                        }
                        
                        $processed++;
                    }
                });

            return redirect()->route('admin.upload')
                ->with('success', "সফলভাবে {$processed} জন ভোটারের নাম ইংরেজিতে রূপান্তর করা হয়েছে!");

        } catch (\Exception $e) {
            return redirect()->route('admin.upload')
                ->with('error', 'রূপান্তর ব্যর্থ: ' . $e->getMessage());
        }
    }
}
