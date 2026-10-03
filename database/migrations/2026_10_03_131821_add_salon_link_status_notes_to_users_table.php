<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'added_by_salon_id')) {
                $table->unsignedBigInteger('added_by_salon_id')->nullable();
                $table->index('added_by_salon_id');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('New');
            }
            if (!Schema::hasColumn('users', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'added_by_salon_id')) {
                $table->dropIndex(['added_by_salon_id']);
                $table->dropColumn('added_by_salon_id');
            }
        });
    }
};