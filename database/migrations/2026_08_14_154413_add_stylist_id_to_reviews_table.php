<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Check karein column exist karti hai ya nahi
            if (!Schema::hasColumn('reviews', 'stylist_id')) {
                $table->unsignedBigInteger('stylist_id')->nullable()->after('salon_id');
                $table->foreign('stylist_id')->references('id')->on('stylists')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['stylist_id']);
            $table->dropColumn('stylist_id');
        });
    }
};