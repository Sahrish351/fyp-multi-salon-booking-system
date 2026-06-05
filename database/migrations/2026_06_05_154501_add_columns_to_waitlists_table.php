<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->time('preferred_time')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'notified', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('notified_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->dropColumn(['preferred_time', 'priority', 'status', 'notes', 'notified_at']);
        });
    }
};