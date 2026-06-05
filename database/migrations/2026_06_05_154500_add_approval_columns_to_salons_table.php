<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salons', function (Blueprint $table) {
            // Check if column exists before adding
            if (!Schema::hasColumn('salons', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
            
            if (!Schema::hasColumn('salons', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            
            if (!Schema::hasColumn('salons', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            
            if (!Schema::hasColumn('salons', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users');
            }
        });
    }

    public function down()
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('rejection_reason');
            $table->dropColumn('approved_at');
            $table->dropColumn('approved_by');
        });
    }
};