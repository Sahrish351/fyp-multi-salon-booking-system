<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('type', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('type', 255)->nullable(false)->change();
        });
    }
};