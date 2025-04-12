    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up()
{
    Schema::create('top_selling_products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->unsignedBigInteger('sales')->default(0);
        $table->unsignedInteger('quantity')->default(0);
        $table->string('trend')->nullable();
        $table->timestamps();
    });
}


        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('top_selling_products');
        }
    };
