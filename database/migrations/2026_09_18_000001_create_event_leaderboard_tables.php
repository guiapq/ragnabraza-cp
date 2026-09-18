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
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event_mvp_kills');
        Schema::dropIfExists('event_speedruns');
    }
};
