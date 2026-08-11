<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Backfill user_id for blogs that were created before the user_id column was added.
     * Uses the first user in the users table (typically the main admin).
     */
    public function up(): void
    {
        // Get the first user (main admin) to assign as default author
        $firstUser = DB::table('users')->orderBy('id')->first();

        if ($firstUser) {
            // Assign user_id to all blogs that have NULL user_id
            DB::table('blogs')
                ->whereNull('user_id')
                ->update(['user_id' => $firstUser->id]);

            // Also update blogs that have invalid user_id (user doesn't exist)
            $validUserIds = DB::table('users')->pluck('id');
            DB::table('blogs')
                ->whereNotIn('user_id', $validUserIds)
                ->update(['user_id' => $firstUser->id]);
        }
    }

    /**
     * Reverse the migrations.
     * Set user_id back to NULL for blogs that were backfilled.
     * Note: This is destructive and only reverses the backfill, not removes the column.
     */
    public function down(): void
    {
        // No reverse - we don't want to remove user assignments
    }
};