<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('app_notifications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('salon_id')
                    ->constrained('users')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('app_notifications', 'type')) {
                $table->string('type')->default('general')->after('title');
                
            }
            if (!Schema::hasColumn('app_notifications', 'appointment_id')) {
                $table->foreignId('appointment_id')->nullable()->after('type')
                    ->constrained('appointments')->nullOnDelete();
            }
            if (!Schema::hasColumn('app_notifications', 'payment_id')) {
                $table->foreignId('payment_id')->nullable()->after('appointment_id')
                    ->constrained('payments')->nullOnDelete();
            }
            if (!Schema::hasColumn('app_notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('message');
            }
            if (!Schema::hasColumn('app_notifications', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('sent_at');
               
            }
            if (!Schema::hasColumn('app_notifications', 'email_to')) {
                $table->string('email_to')->nullable()->after('scheduled_at');
            }
        });
 
        
        Schema::table('app_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('salon_id')->nullable()->change();
        });
    }
 
    public function down(): void
    {
        Schema::table('app_notifications', function (Blueprint $table) {
            $cols = ['user_id', 'type', 'appointment_id', 'payment_id', 'read_at', 'scheduled_at', 'email_to'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('app_notifications', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};