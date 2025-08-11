<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary(); // 1..255 is plenty
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Seed fixed IDs right in the migration (simple and deterministic)
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'editor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'viewer', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
