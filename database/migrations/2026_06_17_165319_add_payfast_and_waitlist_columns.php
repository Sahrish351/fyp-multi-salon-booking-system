<?php
// FILE: database/migrations/2026_06_17_000000_add_payfast_and_waitlist_columns.php
// Run: php artisan make:migration add_payfast_and_waitlist_columns
// then paste this content in, then: php artisan migrate

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'transaction_id')) {
                    $table->string('transaction_id')->nullable()->after('amount');
                }
                if (!Schema::hasColumn('payments', 'sender_number')) {
                    $table->string('sender_number')->nullable()->after('transaction_id');
                }
                if (!Schema::hasColumn('payments', 'screenshot')) {
                    $table->string('screenshot')->nullable()->after('sender_number');
                }
            });
        }

     
        if (Schema::hasTable('appointments') && Schema::hasColumn('appointments', 'status')) {
            // If status is a string column this is a no-op (safe).
            // If it's an ENUM in raw SQL, you may need a separate raw statement —
            // most Laravel scaffolds use a plain string/varchar column for status,
            // so no extra action is usually needed here.
        }

        
        if (!Schema::hasTable('waitlists')) {
            Schema::create('waitlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('salon_id')->constrained('salons')->cascadeOnDelete();
                $table->foreignId('stylist_id')->nullable()->constrained('stylists')->nullOnDelete();
                $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
                $table->date('preferred_date')->nullable();
                $table->string('status')->default('waiting'); // waiting, notified, accepted, expired
                $table->unsignedInteger('position')->default(1);
                $table->timestamps();
            });
        } else {
            Schema::table('waitlists', function (Blueprint $table) {
                if (!Schema::hasColumn('waitlists', 'position')) {
                    $table->unsignedInteger('position')->default(1)->after('status');
                }
                if (!Schema::hasColumn('waitlists', 'preferred_date')) {
                    $table->date('preferred_date')->nullable()->after('service_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Intentionally left non-destructive — do not drop columns on rollback
            // to avoid accidental data loss in a learning/FYP project.
        });
    }
};
