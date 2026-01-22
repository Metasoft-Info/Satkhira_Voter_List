<?php

namespace App\Console\Commands;

use App\Models\Voter;
use App\Helpers\BengaliTransliterator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransliterateVoterNames extends Command
{
    protected $signature = 'voter:transliterate {--batch=500 : Batch size for processing}';
    protected $description = 'Transliterate Bengali names to English for all voters';

    public function handle()
    {
        $batchSize = (int) $this->option('batch');
        $total = Voter::count();
        
        if ($total === 0) {
            $this->error('No voters found in the database.');
            return 1;
        }

        $this->info("Starting transliteration for {$total} voters...");
        $this->info("Batch size: {$batchSize}");
        
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();
        
        $processed = 0;
        $errors = 0;

        Voter::query()
            ->select(['id', 'name', 'father_name', 'mother_name', 'occupation', 'address'])
            ->chunkById($batchSize, function ($voters) use (&$processed, &$errors, $progressBar) {
                foreach ($voters as $voter) {
                    try {
                        $updateData = [];
                        
                        if ($voter->name && !$voter->name_en) {
                            $updateData['name_en'] = BengaliTransliterator::transliterate($voter->name);
                        }
                        
                        if ($voter->father_name && !$voter->father_name_en) {
                            $updateData['father_name_en'] = BengaliTransliterator::transliterate($voter->father_name);
                        }
                        
                        if ($voter->mother_name && !$voter->mother_name_en) {
                            $updateData['mother_name_en'] = BengaliTransliterator::transliterate($voter->mother_name);
                        }
                        
                        if ($voter->occupation && !$voter->profession_en) {
                            $updateData['profession_en'] = BengaliTransliterator::transliterate($voter->occupation);
                        }
                        
                        if ($voter->address && !$voter->address_en) {
                            $updateData['address_en'] = BengaliTransliterator::transliterate($voter->address);
                        }
                        
                        if (!empty($updateData)) {
                            Voter::where('id', $voter->id)->update($updateData);
                        }
                        
                        $processed++;
                    } catch (\Exception $e) {
                        $errors++;
                    }
                    
                    $progressBar->advance();
                }
            });
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("Transliteration completed!");
        $this->info("Processed: {$processed}");
        
        if ($errors > 0) {
            $this->warn("Errors: {$errors}");
        }
        
        // Show sample results
        $this->newLine();
        $this->info("Sample results:");
        $samples = Voter::whereNotNull('name_en')->limit(5)->get(['name', 'name_en', 'father_name', 'father_name_en']);
        
        foreach ($samples as $sample) {
            $this->line("  {$sample->name} → {$sample->name_en}");
            $this->line("  {$sample->father_name} → {$sample->father_name_en}");
            $this->line("  ---");
        }
        
        return 0;
    }
}
