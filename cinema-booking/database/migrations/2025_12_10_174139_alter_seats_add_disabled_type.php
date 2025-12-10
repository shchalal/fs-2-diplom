<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE seats MODIFY seat_type ENUM('regular','vip','disabled') NOT NULL DEFAULT 'regular'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE seats MODIFY seat_type ENUM('regular','vip') NOT NULL DEFAULT 'regular'");
    }
};
