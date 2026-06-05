<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_stylist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('stylist_id')->constrained('stylists')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['service_id', 'stylist_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_stylist');
    }
};