<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE complaints
            MODIFY COLUMN status ENUM(
                'pending',
                'in_progress',
                'resolved',
                'closed',
                'escalated',
                'rejected',
                'awaiting_owner',
                'owner_replied_admin'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        
        DB::table('complaints')
            ->whereIn('status', ['awaiting_owner', 'owner_replied_admin'])
            ->update(['status' => 'escalated']);

        DB::statement("
            ALTER TABLE complaints
            MODIFY COLUMN status ENUM(
                'pending',
                'in_progress',
                'resolved',
                'closed',
                'escalated',
                'rejected'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};