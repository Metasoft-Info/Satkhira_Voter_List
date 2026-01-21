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
        return view('admin.upload', compact('lastUpload'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:50000',
        ]);

        try {
            DB::beginTransaction();

            // Delete existing voter data
            Voter::truncate();

            // Load Excel file
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            $header = array_shift($rows);
            
            $batchSize = 500;
            $batch = [];
            $count = 0;

            foreach ($rows as $row) {
                if (empty($row[0]) && empty($row[1])) continue; // Skip empty rows

                $batch[] = [
                    'serial_no' => $row[0] ?? null,
                    'voter_id' => $row[1] ?? null,
                    'name_bn' => $row[2] ?? null,
                    'name_en' => $row[3] ?? null,
                    'father_name_bn' => $row[4] ?? null,
                    'father_name_en' => $row[5] ?? null,
                    'mother_name_bn' => $row[6] ?? null,
                    'mother_name_en' => $row[7] ?? null,
                    'spouse_name_bn' => $row[8] ?? null,
                    'spouse_name_en' => $row[9] ?? null,
                    'date_of_birth' => $row[10] ?? null,
                    'occupation_bn' => $row[11] ?? null,
                    'occupation_en' => $row[12] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    Voter::insert($batch);
                    $count += count($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                Voter::insert($batch);
                $count += count($batch);
            }

            DB::commit();

            return redirect()->route('admin.upload')
                ->with('success', "Successfully uploaded {$count} voter records!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.upload')
                ->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function resetVoters()
    {
        try {
            DB::beginTransaction();
            
            $count = Voter::count();
            Voter::truncate();
            
            DB::commit();
            
            return redirect()->route('admin.dashboard')
                ->with('success', "Successfully deleted {$count} voter records!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard')
                ->with('error', 'Reset failed: ' . $e->getMessage());
        }
    }
}
