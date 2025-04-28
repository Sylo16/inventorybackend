<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamagedProductsTable extends Migration
{
    public function up()
{
    Schema::create('damaged_products', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('product_name');
        $table->integer('quantity');
        $table->string('reason');
        $table->date('date');
        $table->string('unit_of_measurement'); 
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('damaged_products');
    }
}
