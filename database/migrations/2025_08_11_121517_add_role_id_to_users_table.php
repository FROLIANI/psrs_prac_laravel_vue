<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add column only if missing
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('role_id')->default(3)->after('password');
            });

            // Backfill nulls to 3 (viewer), just in case
            DB::table('users')->whereNull('role_id')->update(['role_id' => 3]);
        }

        // 2) Add FK only if missing
        // Works for MySQL/MariaDB
        $fkExists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'role_id'
              AND REFERENCED_TABLE_NAME = 'roles'
              AND REFERENCED_COLUMN_NAME = 'id'
            LIMIT 1
        ");

        if (!$fkExists) {
            Schema::table('users', function (Blueprint $table) {
                // Name it explicitly so 'down()' can drop it safely
                $table->foreign('role_id', 'users_role_id_foreign')
                      ->references('id')->on('roles');
            });
        }

        // 3) Ensure default is 3 (viewer) even if column existed already
        // (MySQL syntax; skip if you’re on SQLite)
        DB::statement("ALTER TABLE users ALTER role_id SET DEFAULT 3");
    }

    public function down(): void
    {
        // Drop FK if it exists
        $fkExists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'users'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = 'users_role_id_foreign'
            LIMIT 1
        ");

        if ($fkExists) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign('users_role_id_foreign');
            });
        }

        // Drop column if it exists
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role_id');
            });
        }
    }
};
