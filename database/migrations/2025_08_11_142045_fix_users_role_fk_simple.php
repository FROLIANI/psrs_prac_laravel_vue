<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop FK if it exists (Laravel will resolve name like users_role_id_foreign)
            try { $table->dropForeign(['role_id']); } catch (\Throwable $e) {}

            // Re-add FK WITHOUT 'on delete set null'
            $table->foreign('role_id', 'users_role_id_foreign')
                  ->references('id')->on('roles'); // default is RESTRICT
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try { $table->dropForeign(['role_id']); } catch (\Throwable $e) {}
        });
    }
};
