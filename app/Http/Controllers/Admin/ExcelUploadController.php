<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

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
     */
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:50000',
            'upload_mode' => 'required|in:smart,replace',
        ]);

        // Increase execution time and memory for large files
        set_time_limit(600); // 10 minutes
        ini_set('memory_limit', '2048M');

        $mode = $request->input('upload_mode', 'smart');

        try {
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);

            // Load spreadsheet
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $totalRows = $sheet->getHighestRow();

            $newCount = 0;
            $updateCount = 0;
            $skipCount = 0;
            $batchSize = 500;

            // If replace mode, truncate first
            if ($mode === 'replace') {
                Voter::truncate();
            }

            // Get existing voter IDs for comparison (only in smart mode)
            $existingVoters = [];
            if ($mode === 'smart') {
                $existingVoters = Voter::pluck('id', 'voter_id')->toArray();
            }

            $insertBatch = [];
            $updateBatch = [];

            for ($row = 2; $row <= $totalRows; $row++) {
                $rowData = [];
                for ($col = 1; $col <= 17; $col++) {
                    $rowData[] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                }

                // Skip empty rows
                if (empty($rowData[0]) && empty($rowData[10])) {
                    $skipCount++;
                    continue;
                }

                $voterId = $rowData[11] ?? null;
                
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
                    'name' => $rowData[10] ?? null,
                    'voter_id' => $voterId,
                    'father_name' => $rowData[12] ?? null,
                    'mother_name' => $rowData[13] ?? null,
                    'occupation' => $rowData[14] ?? null,
                    'date_of_birth' => $rowData[15] ?? null,
                    'address' => $rowData[16] ?? null,
                    'updated_at' => now(),
                ];

                if ($mode === 'smart' && $voterId && isset($existingVoters[$voterId])) {
                    // Update existing voter
                    $updateBatch[] = array_merge($voterData, ['id' => $existingVoters[$voterId]]);
                    $updateCount++;
                    
                    // Batch update
                    if (count($updateBatch) >= $batchSize) {
                        $this->batchUpdate($updateBatch);
                        $updateBatch = [];
                    }
                } else {
                    // Insert new voter
                    $voterData['created_at'] = now();
                    $insertBatch[] = $voterData;
                    $newCount++;
                    
                    // Batch insert
                    if (count($insertBatch) >= $batchSize) {
                        Voter::insert($insertBatch);
                        $insertBatch = [];
                    }
                }

                // Memory cleanup every 1000 rows
                if ($row % 1000 === 0) {
                    gc_collect_cycles();
                }
            }

            // Insert/Update remaining batches
            if (!empty($insertBatch)) {
                Voter::insert($insertBatch);
            }
            if (!empty($updateBatch)) {
                $this->batchUpdate($updateBatch);
            }

            // Cleanup
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            gc_collect_cycles();

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
}
