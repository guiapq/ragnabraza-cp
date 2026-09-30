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
     * Seed: ancestral-joker-9199
     * Generated: 2026-09-26 20:36:12
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
            ['value' => 'ancestral-joker-9199', 'updated_at' => now()]
        );

        DB::table('world_metadata')->updateOrInsert(
            ['key' => 'world_timestamp'],
            ['value' => '2026_09_26_203612', 'updated_at' => now()]
        );

        // 3. Aplicar o dataset SQL do mundo
        $sqlPath = database_path('data/world_ancestral_joker_9199_2026_09_26_203612.sql');
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
