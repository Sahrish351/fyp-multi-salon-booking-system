<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            
            // Sender information
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            
            // Message details
            $table->string('subject');
            $table->text('message');
            
            // Status tracking
            $table->enum('status', ['unread', 'read', 'replied', 'spam', 'archived'])->default('unread');
            
            // Admin reply
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Read tracking
            $table->timestamp('read_at')->nullable();
            $table->foreignId('read_by')->nullable()->constrained('users')->onDelete('set null');
            
            // IP address for security
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            // Priority
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            
            // Notes for admin
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['email']);
            $table->index(['status']);
            $table->index(['priority']);
            $table->index(['created_at']);
            $table->index(['status', 'priority']);
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};