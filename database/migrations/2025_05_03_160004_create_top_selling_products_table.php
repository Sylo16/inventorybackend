<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
{
Schema::create('top_selling_products', function (Blueprint $table) {
    $table->id();
    $table->string('product_name');
    $table->integer('sales');
    $table->timestamps();
});

}


    
    public function down(): void
    {
        Schema::dropIfExists('top_selling_products');
    }
};
