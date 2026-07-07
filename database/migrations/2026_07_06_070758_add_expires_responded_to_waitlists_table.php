<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            // Check if column exists before adding
            if (!Schema::hasColumn('waitlists', 'expires_at')) {
                $table->timestamp('expires_at')->nullable();
            }
            
            if (!Schema::hasColumn('waitlists', 'responded')) {
                $table->boolean('responded')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            if (Schema::hasColumn('waitlists', 'expires_at')) {
                $table->dropColumn('expires_at');
            }
            
            if (Schema::hasColumn('waitlists', 'responded')) {
                $table->dropColumn('responded');
            }
        });
    }
};