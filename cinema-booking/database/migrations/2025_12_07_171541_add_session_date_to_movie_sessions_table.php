<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movie_sessions', function (Blueprint $table) {
            $table->date('session_date')->default(now()->toDateString())->after('start_time');
        });
    }

    public function down(): void
    {
        Schema::table('movie_sessions', function (Blueprint $table) {
            $table->dropColumn('session_date');
        });
    }

};
