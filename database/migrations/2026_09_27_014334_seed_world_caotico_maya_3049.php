<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Seed: caotico-maya-3049
     * Generated: 2026-09-27 01:43:34
     */
    public function up(): void
    {
        // 1. Garantir tabela de metadata do mundo
        if (!Schema::hasTable('world_metadata')) {
            Schema::create('world_metadata', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // 2. Registrar a seed ativa
        DB::table('world_metadata')->updateOrInsert(
            ['key' => 'active_seed'],
            ['value' => 'caotico-maya-3049', 'updated_at' => now()]
        );

        DB::table('world_metadata')->updateOrInsert(
            ['key' => 'world_timestamp'],
            ['value' => '2026_09_27_014334', 'updated_at' => now()]
        );

        // 3. Aplicar o dataset SQL do mundo
        $sqlPath = database_path('data/world_caotico_maya_3049_2026_09_27_014334.sql');
        if (File::exists($sqlPath)) {
            DB::unprepared(File::get($sqlPath));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversão da seed
    }
};
