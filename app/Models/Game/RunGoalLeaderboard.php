<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class RunGoalLeaderboard extends Model
{
    protected $table = 'run_goal_leaderboard';

    protected $fillable = [
        'char_id',
        'char_name',
        'class',
        'seed',
        'fragments_destroyed',
        'delivered_final_blow',
        'maps_conquered',
        'mvp_kills',
        'mob_kills',
        'pvp_kills',
        'deaths',
        'level_99_time',
        'total_score',
        'medals',
        'rank_position',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class, 'char_id', 'char_id');
    }

    public function scopeForCurrentSeed($query, $seed = null)
    {
        if (!$seed) {
            $metadata = \DB::table('world_metadata')->where('key', 'active_seed')->first();
            $seed = $metadata ? $metadata->value : 'v3-world';
        }
        return $query->where('seed', $seed);
    }
}
