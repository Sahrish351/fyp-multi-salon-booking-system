<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('salon_id')->constrained('salons')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Complaint details
            $table->enum('type', ['service', 'staff', 'payment', 'product', 'other'])->default('other');
            $table->string('subject', 255);
            $table->text('description');
            $table->string('image')->nullable();
            
            // ✅ STATUS FLOW - Requirement ke mutabiq
            $table->enum('status', [
                'pending',
                'in_progress',
                'resolved',
                'closed',
                'escalated',
                'rejected'
            ])->default('pending');
            
            // Owner actions
            $table->text('owner_reply')->nullable();
            $table->timestamp('owner_replied_at')->nullable();
            
            // Client actions (Accept/Escalate)
            $table->enum('client_action', ['accept', 'escalate'])->nullable();
            $table->timestamp('client_actioned_at')->nullable();
            
            // Admin actions
            $table->text('admin_response')->nullable();
            $table->timestamp('admin_actioned_at')->nullable();
            
            // Rejection
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['client_id', 'status']);
            $table->index(['salon_id', 'status']);
            $table->index(['appointment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};