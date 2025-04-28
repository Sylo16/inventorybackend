<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
    
            // Sales Stats
            $table->unsignedBigInteger('total_sales')->default(0);
            $table->unsignedBigInteger('today_sales')->default(0);
            $table->unsignedBigInteger('month_sales')->default(0);
            $table->string('sales_trend')->nullable();
    
            // Revenue Stats
            $table->unsignedBigInteger('total_revenue')->default(0);
            $table->unsignedBigInteger('gross_revenue')->default(0);
            $table->unsignedBigInteger('net_revenue')->default(0);
            $table->string('revenue_trend')->nullable();
    
            // Critical Alerts
            $table->unsignedInteger('critical_alerts')->default(0);
            $table->unsignedInteger('low_stock')->default(0);
            $table->unsignedInteger('out_of_stock')->default(0);
            $table->string('alert_trend')->nullable();
    
            $table->timestamps();
        });
    
    
    }

    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
