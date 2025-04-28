<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
{
    Schema::create('category_sales', function (Blueprint $table) {
        $table->id();
        $table->string('category');
        $table->integer('sales');
        $table->string('color')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('category_sales');
    }
};
