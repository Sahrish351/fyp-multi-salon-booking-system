<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('waitlists', function (Blueprint $table) {
            // ✅ Check if column exists before adding
            if (!Schema::hasColumn('waitlists', 'preferred_time')) {
                $table->time('preferred_time')->nullable()->after('preferred_date');
            }
            
            if (!Schema::hasColumn('waitlists', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            }
            
            if (!Schema::hasColumn('waitlists', 'status')) {
                $table->enum('status', ['pending', 'notified', 'cancelled'])->default('pending');
            }
            
            if (!Schema::hasColumn('waitlists', 'notes')) {
                $table->text('notes')->nullable();
            }
            
            if (!Schema::hasColumn('waitlists', 'notified_at')) {
                $table->timestamp('notified_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $columns = ['preferred_time', 'priority', 'status', 'notes', 'notified_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('waitlists', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};