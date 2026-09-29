<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'escalation_reason')) {
                $table->text('escalation_reason')->nullable()->after('client_actioned_at');
            }
            if (!Schema::hasColumn('complaints', 'admin_question')) {
                $table->text('admin_question')->nullable()->after('admin_actioned_at');
            }
            if (!Schema::hasColumn('complaints', 'admin_question_at')) {
                $table->timestamp('admin_question_at')->nullable()->after('admin_question');
            }
            if (!Schema::hasColumn('complaints', 'owner_deadline_at')) {
                $table->timestamp('owner_deadline_at')->nullable()->after('admin_question_at');
            }
            if (!Schema::hasColumn('complaints', 'owner_statement')) {
                $table->text('owner_statement')->nullable()->after('owner_deadline_at');
            }
            if (!Schema::hasColumn('complaints', 'owner_statement_at')) {
                $table->timestamp('owner_statement_at')->nullable()->after('owner_statement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $columns = [
                'escalation_reason',
                'admin_question',
                'admin_question_at',
                'owner_deadline_at',
                'owner_statement',
                'owner_statement_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('complaints', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};