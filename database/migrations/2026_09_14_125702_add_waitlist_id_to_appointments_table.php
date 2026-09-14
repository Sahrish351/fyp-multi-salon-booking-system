<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('waitlist_id')
                  ->nullable()
                  ->after('time_slot_id')
                  ->constrained('waitlists')
                  ->nullOnDelete();
        });
    }
 
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['waitlist_id']);
            $table->dropColumn('waitlist_id');
        });
    }
};
 