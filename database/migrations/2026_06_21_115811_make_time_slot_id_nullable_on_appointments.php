<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
 
return new class extends Migration
{
    public function up(): void
    {
       
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointments_time_slot_id_foreign');
        });
 
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('time_slot_id')->nullable()->change();
        });
 
        
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('time_slot_id')
                  ->references('id')->on('time_slots')
                  ->nullOnDelete();
        });
    }
 
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointments_time_slot_id_foreign');
        });
 
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('time_slot_id')->nullable(false)->change();
        });
 
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('time_slot_id')
                  ->references('id')->on('time_slots');
        });
    }
};
 