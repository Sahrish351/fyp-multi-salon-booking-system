<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointment_services', function (Blueprint $table) {
            $table->id();
            
            // Appointment ID (which appointment)
            $table->foreignId('appointment_id')
                  ->constrained('appointments')
                  ->onDelete('cascade');
            
            // Service ID (which service)
            $table->foreignId('service_id')
                  ->constrained('services')
                  ->onDelete('cascade');
            
            // Stylist ID (optional - which stylist for this service)
            $table->foreignId('stylist_id')
                  ->nullable()
                  ->constrained('stylists')
                  ->onDelete('set null');
            
            // Price at the time of booking (in case service price changes later)
            $table->decimal('price', 10, 2);
            
            // Duration in minutes
            $table->integer('duration');
            
            // Discount applied (if any)
            $table->decimal('discount', 10, 2)->default(0);
            
            // Final price after discount
            $table->decimal('final_price', 10, 2);
            
            // Quantity (if same service multiple times)
            $table->integer('quantity')->default(1);
            
            // Any notes for this specific service in appointment
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['appointment_id']);
            $table->index(['service_id']);
            $table->index(['stylist_id']);
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('appointment_services');
    }
};