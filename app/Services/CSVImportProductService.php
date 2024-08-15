<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\TblProductData;
use League\Csv\Reader;

class CSVImportProductService
{
    protected $csvPath;

    public function __construct($csvPath)
    {
        $this->csvPath = $csvPath;
    }

    public function import()
    {

        $count_all = 0;
        $count_imported = 0;
        $count_failed = 0;
        $errors = [];

        $csvFile = storage_path("app/public/{$this->csvPath}");

        if (!File::exists($csvFile)) {
            throw new \Exception('CSV file not found');
        }

        $reader = Reader::createFromPath($csvFile);
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        foreach ($records as $offset => $record) {
            $count_all++;

            // Validation logic here
            $validator = Validator::make($record, [
                "Product Code" => "required|string|max:10|unique:App\Models\TblProductData,strProductCode",  // Product Code
                "Product Name" => "required|string|max:50",  // Product Name
                "Product Description" => "required|string|max:255", // Product Description
                "Stock" => "required|numeric", // Stock level
                "Cost in GBP" => "required|between:0,1000.00", // Price(Cost in GBP)
                "Discontinued" => "string|max:10", // Discontinued
            ]);

            if ($validator->fails()) {
                $count_failed++;
                $errors[] = $validator->errors()->all();
            } else {

                // store to DB
                $addNewProduct = TblProductData::create([
                    "strProductCode" => $record["Product Code"],
                    "strProductName" => $record["Product Name"],
                    "strProductDesc" => $record["Product Description"],
                ]);

                if($addNewProduct){
                    $count_imported++;
                }else{
                    $count_failed++;
                }

            }
        }

        // $handle = fopen($csvFile, 'r');
        // //$header = fgetcsv($csvFile);

        // while (($row = fgetcsv($handle)) !== false) {

        //     // $columnName = array("ProductCode","ProductName","ProductDescription", "StockLevel", "Price", "Discontinued");
        //     // $data[$count_all] = array_combine($columnName, $row);

        // }

        // fclose($handle);

        return [
            'success' => true,
            'errors' => $errors,
            'count_all' => $count_all,
            'count_imported' => $count_imported,
            'count_failed' => $count_failed,
        ];
    }
}
