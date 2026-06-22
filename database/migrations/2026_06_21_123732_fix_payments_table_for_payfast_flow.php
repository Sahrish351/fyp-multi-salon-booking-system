<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        DB::statement("ALTER TABLE payments MODIFY sender_number VARCHAR(255) NULL");

       
        DB::statement("ALTER TABLE payments MODIFY method ENUM('easypaisa','jazzcash','payfast','card') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY method ENUM('easypaisa','jazzcash') NOT NULL");
        DB::statement("ALTER TABLE payments MODIFY sender_number VARCHAR(255) NOT NULL");
    }
};
