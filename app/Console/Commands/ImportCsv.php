<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {param?}';

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
        $this->info("Sending email to: {$param}!");

        $csvFile = storage_path('app/public/stock.csv');

        if (!File::exists($csvFile)) {
            $this->error('CSV file not found.');
            return Command::FAILURE;
        }

        $data = [];
        $handle = fopen($csvFile, 'r');

        $headers = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {

            $this->error("$row[0]|| $row[1] || $row[2]");
            //$data[] = array_combine($headers, $row);
        }

        fclose($handle);

        return Command::SUCCESS;
    }
}
