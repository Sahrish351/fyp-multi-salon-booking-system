<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('stylist_id')->nullable()->after('service_id');
            $table->foreign('stylist_id')->references('id')->on('stylists')->onDelete('set null');
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