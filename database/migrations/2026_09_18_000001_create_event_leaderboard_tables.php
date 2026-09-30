<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('event_speedruns')) {
            Schema::create('event_speedruns', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('char_id')->unique();
                $table->string('name', 30);
                $table->unsignedSmallInteger('class');
                $table->unsignedInteger('base_level')->default(99);
                $table->unsignedInteger('job_level')->default(50);
                $table->unsignedInteger('total_seconds');
                $table->dateTime('achieved_at');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('event_mvp_kills')) {
            Schema::create('event_mvp_kills', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('char_id');
                $table->string('char_name', 30);
                $table->unsignedInteger('mob_id');
                $table->string('mob_name', 50);
                $table->dateTime('killed_at');
                $table->timestamps();

                $table->index('char_id');
                $table->index('mob_id');
                $table->index('killed_at');
            });
        if (!Schema::hasTable('run_goal_leaderboard')) {
            Schema::create('run_goal_leaderboard', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('char_id');
                $table->string('char_name', 30);
                $table->unsignedSmallInteger('class')->default(0);
                $table->string('seed', 64);
                $table->unsignedInteger('fragments_destroyed')->default(0);
                $table->boolean('delivered_final_blow')->default(false);
                $table->unsignedInteger('maps_conquered')->default(0);
                $table->unsignedInteger('mvp_kills')->default(0);
                $table->unsignedInteger('mob_kills')->default(0);
                $table->unsignedInteger('pvp_kills')->default(0);
                $table->unsignedInteger('deaths')->default(0);
                $table->unsignedInteger('level_99_time')->default(0);
                $table->integer('total_score')->default(0);
                $table->string('medals', 255)->default('');
                $table->unsignedInteger('rank_position')->default(0);
                $table->timestamps();

                $table->unique(['char_id', 'seed']);
                $table->index(['seed', 'total_score']);
            });
        }

        if (!Schema::hasTable('run_metadata')) {
            Schema::create('run_metadata', function (Blueprint $table) {
                $table->string('seed', 64)->primary();
                $table->boolean('is_finished')->default(false);
                $table->dateTime('finished_at')->nullable();
                $table->string('winning_killer', 30)->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('run_metadata');
        Schema::dropIfExists('run_goal_leaderboard');
        Schema::dropIfExists('event_mvp_kills');
        Schema::dropIfExists('event_speedruns');
    }
};
