<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
 
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('easypaisa','jazzcash','bank','cash','online') NOT NULL");
    }
 
    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('easypaisa','jazzcash','cash','online') NOT NULL");
    }
};