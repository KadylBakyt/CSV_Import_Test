<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\TblProductData;
use League\Csv\Reader;

class CSVImportProductService
{
    protected string $csvPath;
    protected ?string $test = null;

    public function __construct($csvPath, $test)
    {
        $this->csvPath = $csvPath;
        $this->test = $test;
    }

    protected function rules(): array
    {
        return [
            "ProductCode" => "required|string|max:10|unique:App\Models\TblProductData,strProductCode",
            "ProductName" => "required|string|max:50",
            "ProductDesc" => "required|string|max:255",
            "StockLevel" => "required|numeric|min:5",
            "Cost" => "required|numeric|min:10|max:1000",
            "Discontinued" => "string|max:10",
        ];
    }

    protected function messages(): array
    {
        return [
            'ProductCode' => 'The :attribute :input is already stored in DB'
        ];
    }

    public function import()
    {

        $line_number = 0;
        $count_all = 0;
        $count_imported = 0;
        $count_success = 0;
        $count_failed = 0;
        $errors = [];
        $expectedColumnCount = 6;

        if (!File::exists($this->csvPath)) {
            throw new \Exception('CSV file not found');
        }

        $extension = pathinfo($this->csvPath, PATHINFO_EXTENSION);
        if ($extension !== 'csv') {
            throw new \Exception('The file field must be a file of type: csv, text/csv.');
        }

        $reader = Reader::createFromPath($this->csvPath);
        $header = $reader->fetchOne();

        if (!$header || count($header) < $expectedColumnCount) {
            throw new \Exception('Invalid CSV header!');
        }

        foreach ($reader as $row) {
            $line_number++;

            if($line_number == 1) continue;
            $count_all++;

            // Check for empty rows
            if (empty($row)) {
                //throw new \Exception("Empty row found (in $line_number line)!");
                $count_failed++;
                continue;
            }

             // Check for missing columns
            if (count($row) !== count($header)) {
                //throw new \Exception("Missing columns (in $line_number line)!");
                $errors[] = "Missing columns (in $line_number line)!";
                $count_failed++;
                continue;
            }

            $columnNames = ["ProductCode", "ProductName", "ProductDesc", "StockLevel", "Cost", "Discontinued"];
            $record = array_combine($columnNames, $row);

            // Validation logic here
            $validator = Validator::make(
                $record,
                $this->rules(),
                $this->messages()
            );

            if ($validator->fails()) {
                $count_failed++;
                $errors[] = $validator->errors()->all();
            } else {

                if ($this->test != null && (strtoupper($this->test) == 'TEST')){
                    $count_success++;
                }else{

                    $discontinued = (strtoupper($row[5]) == 'YES') ? true : false;
                    $dtmDiscontinued = ($discontinued) ? date('Y-m-d H:i:s') : NULL;
                    // store to DB
                    $addNewProduct = TblProductData::create([
                        "strProductCode" => $row[0],
                        "strProductName" => $row[1],
                        "strProductDesc" => $row[2],
                        "intStockLevel" => (int)$row[3],
                        "decimalPrice" => round($row[4], 2),
                        "dtmAdded" => date('Y-m-d H:i:s'),
                        "dtmDiscontinued" => $dtmDiscontinued,
                        "boolDiscontinued" => (strtoupper($row[5]) == 'YES') ? true : false,
                    ]);

                    if($addNewProduct) {
                        $count_imported++;
                    }
                }
            }
        }

        return [
            'success' => true,
            'errors' => $errors,
            'count_all' => $count_all,
            'count_imported' => $count_imported,
            'count_success' => $count_success,
            'count_failed' => $count_failed,
        ];
    }

    public function importTest()
    {

        $line_number = 0;
        $count_all = 0;
        $count_imported = 0;
        $count_success = 0;
        $count_failed = 0;
        $errors = [];
        $expectedColumnCount = 6;

        if (!File::exists($this->csvPath)) {
            // throw new \Exception('CSV file not found');
            return [
                'success' => false,
                'errors' => 'CSV file not found',
            ];
        }

        $extension = pathinfo($this->csvPath, PATHINFO_EXTENSION);
        if ($extension !== 'csv') {
            return [
                'success' => false,
                'errors' => 'The file field must be a file of type: csv, text/csv.',
            ];
        }

        $reader = Reader::createFromPath($this->csvPath);
        $header = $reader->fetchOne();

        if (!$header || count($header) < $expectedColumnCount) {
            // throw new \Exception('Invalid CSV header!');
            return [
                'success' => false,
                'errors' => 'Invalid CSV header!',
            ];
        }

        foreach ($reader as $row) {
            $line_number++;

            if($line_number == 1) continue;
            $count_all++;

            // Check for empty rows
            if (empty($row)) {
                // throw new \Exception("Empty row found (in $line_number line)!");
                return [
                    'success' => false,
                    'errors' => "Empty row found (in $line_number line)!",
                ];
                $count_failed++;
                continue;
            }

             // Check for missing columns
            if (count($row) !== count($header)) {
                //throw new \Exception("Missing columns (in $line_number line)!");
                $errors[] = "Missing columns (in $line_number line)!";
                return [
                    'success' => false,
                    'errors' => "Empty row found (in $line_number line)!",
                ];
                $count_failed++;
                continue;
            }

            $columnNames = ["ProductCode", "ProductName", "ProductDesc", "StockLevel", "Cost", "Discontinued"];
            $record = array_combine($columnNames, $row);

            // Validation logic here
            $validator = Validator::make(
                $record,
                $this->rules(),
                $this->messages()
            );

            if ($validator->fails()) {
                $count_failed++;
                $errors[] = $validator->errors()->all();
            } else {

                if ($this->test != null && (strtoupper($this->test) == 'TEST')){
                    $count_success++;
                }else{

                    $discontinued = (strtoupper($row[5]) == 'YES') ? true : false;
                    $dtmDiscontinued = ($discontinued) ? date('Y-m-d H:i:s') : NULL;
                    // store to DB
                    $addNewProduct = TblProductData::create([
                        "strProductCode" => $row[0],
                        "strProductName" => $row[1],
                        "strProductDesc" => $row[2],
                        "intStockLevel" => (int)$row[3],
                        "decimalPrice" => round($row[4], 2),
                        "dtmAdded" => date('Y-m-d H:i:s'),
                        "dtmDiscontinued" => $dtmDiscontinued,
                        "boolDiscontinued" => (strtoupper($row[5]) == 'YES') ? true : false,
                    ]);

                    if($addNewProduct) {
                        $count_imported++;
                    }
                }
            }
        }

        return [
            'success' => true,
            'errors' => $errors,
            'count_all' => $count_all,
            'count_imported' => $count_imported,
            'count_success' => $count_success,
            'count_failed' => $count_failed,
        ];
    }
}
