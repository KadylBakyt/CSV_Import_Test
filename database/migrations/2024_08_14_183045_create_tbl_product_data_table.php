<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tblProductData', function (Blueprint $table) {
            $table->increments('intProductDataId');
            $table->string('strProductName', length: 50);
            $table->string('strProductDesc', length: 255);
            $table->string('strProductCode', 10)->nullable(false)->unique();
            $table->dateTime('dtmAdded')->nullable();
            $table->dateTime('dtmDiscontinued')->nullable();
            $table->timestamp('stmTimestamp')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
        });

        DB::statement("ALTER TABLE tblProductData ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores product data'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblProductData',
            function (Blueprint $table) {
                $table->dropColumn('stmTimestamp');
            }
        );
    }
};
