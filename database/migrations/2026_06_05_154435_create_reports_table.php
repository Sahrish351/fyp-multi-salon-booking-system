<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('salon_id')->nullable()->constrained('salons')->onDelete('set null');
            $table->enum('type', ['daily_sales', 'monthly_sales', 'appointments', 'payments', 'clients']);
            $table->enum('format', ['pdf', 'excel']);
            $table->string('file_path')->nullable();
            $table->date('from_date');
            $table->date('to_date');
            $table->enum('status', ['generating', 'ready', 'failed'])->default('generating');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
