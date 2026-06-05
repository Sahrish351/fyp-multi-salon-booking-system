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
        Schema::create('appointment_reschedules', function (Blueprint $table) {
            $table->id();
            
            // Original appointment being rescheduled
            $table->foreignId('appointment_id')
                  ->constrained('appointments')
                  ->onDelete('cascade');
            
            // Old date & time (before reschedule)
            $table->datetime('old_start_time');
            $table->datetime('old_end_time')->nullable();
            
            // New date & time (after reschedule)
            $table->datetime('new_start_time');
            $table->datetime('new_end_time')->nullable();
            
            // Reason for reschedule
            $table->text('reason')->nullable();
            
            // Status: pending, approved, rejected, cancelled
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            
            // Who requested the reschedule?
            $table->enum('requested_by', ['client', 'owner', 'admin'])->default('client');
            
            // Approved/rejected by (user ID)
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // When it was approved/rejected
            $table->timestamp('approved_at')->nullable();
            
            // Admin/owner notes
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['appointment_id']);
            $table->index(['status']);
            $table->index(['requested_by']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_reschedules');
    }
};