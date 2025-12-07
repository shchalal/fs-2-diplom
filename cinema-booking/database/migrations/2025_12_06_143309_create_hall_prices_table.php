<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hall_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hall_id')
                ->constrained('cinema_halls')
                ->onDelete('cascade');

            // Цены
            $table->unsignedInteger('regular_price')->default(0);
            $table->unsignedInteger('vip_price')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hall_prices');
    }
};
