<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('waitlists', 'time_slot_id')) {
            return;
        }

        Schema::table('waitlists', function (Blueprint $table) {
            $table->unsignedBigInteger('time_slot_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->unsignedBigInteger('time_slot_id')->nullable(false)->change();
        });
    }
};