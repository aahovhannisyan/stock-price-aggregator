<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('stock_id');
            $table->timestamp('timestamp');
            $table->decimal('open', 10, 4);
            $table->decimal('high', 10, 4);
            $table->decimal('low', 10, 4);
            $table->decimal('close', 10, 4);
            $table->integer('volume');
            $table->timestamps();

            $table->unique(['stock_id', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_prices');
    }
};
