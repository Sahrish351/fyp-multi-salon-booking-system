<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
       
        DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM(
            'pending',
            'in_progress',
            'resolved',
            'closed',
            'escalated',
            'rejected',
            'awaiting_owner',
            'owner_replied_admin'
        ) DEFAULT 'pending'");

       
        Schema::table('complaints', function (Blueprint $table) {
            $table->text('escalation_reason')->nullable()->after('client_actioned_at');
            $table->text('admin_question')->nullable()->after('admin_response');
            $table->timestamp('admin_question_at')->nullable()->after('admin_question');
            $table->timestamp('owner_deadline_at')->nullable()->after('admin_question_at');
            $table->text('owner_statement')->nullable()->after('owner_deadline_at');
            $table->timestamp('owner_statement_at')->nullable()->after('owner_statement');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn([
                'escalation_reason',
                'admin_question',
                'admin_question_at',
                'owner_deadline_at',
                'owner_statement',
                'owner_statement_at',
            ]);
        });

        DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM(
            'pending',
            'in_progress',
            'resolved',
            'closed',
            'escalated',
            'rejected'
        ) DEFAULT 'pending'");
    }
};