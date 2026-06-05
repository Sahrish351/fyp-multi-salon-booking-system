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
        Schema::create('salon_working_hours', function (Blueprint $table) {
            $table->id();
            
            // Salon ID
            $table->foreignId('salon_id')
                  ->constrained('salons')
                  ->onDelete('cascade');
            
            // Day of week
            $table->enum('day_of_week', [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 
                'Friday', 'Saturday', 'Sunday'
            ]);
            
            // Opening time
            $table->time('start_time');
            
            // Closing time
            $table->time('end_time');
            
            // Break start time (lunch break)
            $table->time('break_start')->nullable();
            
            // Break end time
            $table->time('break_end')->nullable();
            
            // Is salon closed on this day?
            $table->boolean('is_closed')->default(false);
            
            // Is holiday? (special case)
            $table->boolean('is_holiday')->default(false);
            
            // Holiday name if is_holiday = true
            $table->string('holiday_name')->nullable();
            
            // Slot duration in minutes (e.g., 30, 45, 60)
            $table->integer('slot_duration')->default(30);
            
            // Buffer time between appointments in minutes
            $table->integer('buffer_time')->default(0);
            
            // Max bookings allowed in this time slot
            $table->integer('max_bookings_per_slot')->default(1);
            
            // Special notes for this day
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Ensure unique working hours per salon per day
            $table->unique(['salon_id', 'day_of_week']);
            
            // Indexes for faster queries
            $table->index(['salon_id', 'is_closed']);
            $table->index(['salon_id', 'is_holiday']);
            $table->index(['day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salon_working_hours');
    }
};