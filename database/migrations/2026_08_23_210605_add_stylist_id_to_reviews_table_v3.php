<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('reviews', 'stylist_id')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('stylist_id')->nullable()->constrained('stylists')->onDelete('set null');
            $table->index('stylist_id');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('reviews', 'stylist_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropForeign(['stylist_id']);
                $table->dropColumn('stylist_id');
            });
        }
    }
};