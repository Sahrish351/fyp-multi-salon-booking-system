<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
           
            if (!Schema::hasColumn('reviews', 'is_flagged')) {
                $table->boolean('is_flagged')->default(false)->after('is_approved');
            }
           
            if (!Schema::hasColumn('reviews', 'owner_reply')) {
                $table->text('owner_reply')->nullable()->after('is_flagged');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['is_flagged', 'owner_reply']);
        });
    }
};