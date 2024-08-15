<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CSVImportProductService;
use Illuminate\Support\Facades\File;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {fileName} {param?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the CSV file, parse the contents and then insert
the data into DB(to table: tblProductData)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $param = $this->argument('param');
        $fileName = $this->argument('fileName');
        $csvFile = storage_path("app/public/{$fileName}");

        if (!File::exists($csvFile)) {
            $this->error('CSV file not found !');
            return Command::FAILURE;
        }

        $service = new CSVImportProductService($fileName);
        $result = $service->import();

        $all_processes_items_count = $result['count_all'];
        $this->info(" $all_processes_items_count items were processed");

        $successful_items_count = $result['count_imported'];
        $this->info(" $successful_items_count items were successful");

        $failed_items_count = $result['count_failed'];
        $this->info(" $failed_items_count items were skipped");

        //dd($result['errors']);

        return Command::SUCCESS;

    }
}
