<?php

namespace App\Console\Commands;

use App\Models\Voter;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use Illuminate\Support\Facades\DB;

class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow = 0;

    public function __construct($startRow, $endRow)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    public function readCell($columnAddress, $row, $worksheetName = '')
    {
        if ($row == 1) return true; // Always read header
        return ($row >= $this->startRow && $row <= $this->endRow);
    }
}

class ImportVotersCommand extends Command
{
    protected $signature = 'voters:import {file}';
    protected $description = 'Import voters from Excel file';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info('Starting import...');
        
        // Truncate existing data
        DB::beginTransaction();
        Voter::truncate();
        
        try {
            // Get total rows without loading entire file
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $chunkSize = 100;
            
            // First pass: count rows
            $this->info('Counting rows...');
            $spreadsheet = $reader->load($filePath);
            $totalRows = $spreadsheet->getActiveSheet()->getHighestRow();
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            gc_collect_cycles();
            
            $this->info("Total rows to process: " . ($totalRows - 1));
            
            $batchSize = 500;
            $batch = [];
            $totalImported = 0;
            
            // Process in chunks
            $progressBar = $this->output->createProgressBar($totalRows - 1);
            $progressBar->start();
            
            for ($startRow = 2; $startRow <= $totalRows; $startRow += $chunkSize) {
                $endRow = min($startRow + $chunkSize - 1, $totalRows);
                
                // Create new reader for this chunk
                $chunkReader = IOFactory::createReader('Xlsx');
                $chunkReader->setReadDataOnly(true);
                $chunkReader->setReadFilter(new ChunkReadFilter($startRow, $endRow));
                
                $spreadsheet = $chunkReader->load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                
                foreach ($sheet->getRowIterator() as $row) {
                    if ($row->getRowIndex() == 1) continue; // Skip header
                    
                    $rowData = [];
                    for ($col = 1; $col <= 17; $col++) {
                        $rowData[] = $sheet->getCellByColumnAndRow($col, $row->getRowIndex())->getValue();
                    }
                    
                    if (empty($rowData[0]) && empty($rowData[10])) continue;
                    
                    $batch[] = [
                        'serial_no' => $rowData[0] ?? null,
                        'upazila' => $rowData[1] ?? null,
                        'union' => $rowData[2] ?? null,
                        'ward' => $rowData[3] ?? null,
                        'area_code' => $rowData[4] ?? null,
                        'area_name' => $rowData[5] ?? null,
                        'gender' => $rowData[6] ?? null,
                        'center_no' => $rowData[7] ?? null,
                        'center_name' => $rowData[8] ?? null,
                        'voter_id' => $rowData[11] ?? null,
                        'name' => $rowData[10] ?? null,
                        'father_name' => $rowData[12] ?? null,
                        'mother_name' => $rowData[13] ?? null,
                        'occupation' => $rowData[14] ?? null,
                        'date_of_birth' => $rowData[15] ?? null,
                        'address' => $rowData[16] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    if (count($batch) >= $batchSize) {
                        Voter::insert($batch);
                        $totalImported += count($batch);
                        $batch = [];
                    }
                    
                    $progressBar->advance();
                }
                
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
                gc_collect_cycles();
            }
            
            // Insert remaining batch
            if (!empty($batch)) {
                Voter::insert($batch);
                $totalImported += count($batch);
            }
            
            $progressBar->finish();
            $this->newLine(2);
            
            DB::commit();
            
            $this->info("Import completed successfully!");
            $this->info("Total voters imported: {$totalImported}");
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }
    }
}
