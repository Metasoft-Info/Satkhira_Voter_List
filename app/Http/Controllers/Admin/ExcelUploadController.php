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
    private int $startRow;
    private int $endRow;

    public function __construct(int $startRow, int $endRow)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
    {
        // Always read row 1 (header) and rows within our chunk
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
     * Smart Upload - Only update changed data, don't replace all
     * Uses chunk reading for memory efficiency
     */
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:100000',
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

        try {
            // Save uploaded file temporarily
            $file = $request->file('excel_file');
            $tempPath = $file->getRealPath();
            
            // Use chunk reader filter to read in smaller pieces
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            
            // First, get total rows count only
            $spreadsheetInfo = $reader->listWorksheetInfo($tempPath);
            $totalRows = $spreadsheetInfo[0]['totalRows'] ?? 0;
            
            if ($totalRows <= 1) {
                return redirect()->route('admin.upload')
                    ->with('error', 'এক্সেল ফাইলে কোন ডেটা নেই!');
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
                ? "স্মার্ট আপলোড সফল! নতুন: {$newCount}, আপডেট: {$updateCount}, স্কিপ: {$skipCount}"
                : "রিপ্লেস আপলোড সফল! মোট: " . ($newCount + $updateCount) . " রেকর্ড";

            return redirect()->route('admin.upload')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.upload')
                ->with('error', 'আপলোড ব্যর্থ: ' . $e->getMessage());
        }
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
