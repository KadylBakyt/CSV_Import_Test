<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblProductData extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'tblProductData';

    protected $fillable = [
        'strProductName',
        'strProductDesc',
        'strProductCode',
        'dtmAdded',
        'dtmDiscontinued',
        'intStockLevel',
        'decimalPrice',
        'boolDiscontinued'
    ];
}
