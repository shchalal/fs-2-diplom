<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE seats MODIFY seat_type ENUM('regular','vip','disabled') NOT NULL DEFAULT 'regular'"
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE seats MODIFY seat_type ENUM('regular','vip') NOT NULL DEFAULT 'regular'"
            );
        }
    }
};
