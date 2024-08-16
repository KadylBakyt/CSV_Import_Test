<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use App\Services\CSVImportProductService;

class CSVImportProductTest extends TestCase
{
    /**
     * Test if CSV file not exist.
     */
    public function test_not_existing_file()
    {
        $fakeFileUrl = '/var/www/XXXXX.csv';
        $service = new CSVImportProductService($fakeFileUrl, 'test');
        $result = $service->importTest();

        $this->assertFalse($result['success']);
    }

    /**
     * Test invalid file type.
     */
    public function test_check_invalid_file_type()
    {
        Storage::fake('local');

        // Create a txt file with some content
        $csvData = "ProductCode, ProductName, ProductDesc, StockLevel, Cost, Discontinued \n" .
                    'P0001,TV, 32” Tv, 10, 399.99, ""';
        Storage::disk('local')->put('test.txt', $csvData);

        // Get the file path
        $filePath = Storage::disk('local')->path('test.csv');

        $service = new CSVImportProductService($filePath, 'test');
        $result = $service->importTest();

        $this->assertFalse($result['success']);
    }

    /**
     * Test invalid CSV header.
     */
    public function test_check_invalid_csv_header_type()
    {
        Storage::fake('local');

        // Create a CSV file with some content
        $csvData = "column1, column2, column3,\n" .
                   "first, second, third \n";
        Storage::disk('local')->put('test.csv', $csvData);

        // Get the file path
        $filePath = Storage::disk('local')->path('test.csv');

        $service = new CSVImportProductService($filePath, 'test');
        $result = $service->importTest();

        $this->assertFalse($result['success']);
    }

    /**
     * Test empty row in CSV file.
     */
    public function test_check_empty_row()
    {
        Storage::fake('local');

        // Create a CSV file with some content
        $csvData = "column1, column2, column3,\n" .
                   ", ,  \n";
        Storage::disk('local')->put('test.csv', $csvData);

        // Get the file path
        $filePath = Storage::disk('local')->path('test.csv');

        $service = new CSVImportProductService($filePath, 'test');
        $result = $service->importTest();

        $this->assertFalse($result['success']);
    }

    /**
     * Test missing columns in CSV file.
     */
    public function test_check_missing_columns_in_csv_file()
    {
        Storage::fake('local');

        // Create a CSV file with some content
        $csvData = "ProductCode, ProductName, ProductDesc, StockLevel, Cost, Discontinued \n" .
                    'P0001,TV, 32” Tv, 10,';
        Storage::disk('local')->put('test.csv', $csvData);

        // Get the file path
        $filePath = Storage::disk('local')->path('test.csv');

        $service = new CSVImportProductService($filePath, 'test');
        $result = $service->importTest();

        $this->assertFalse($result['success']);
    }

}
