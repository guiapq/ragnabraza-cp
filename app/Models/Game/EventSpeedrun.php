<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class EventSpeedrun extends Model
{
    protected $table = 'event_speedruns';

    protected $fillable = [
        'char_id',
        'name',
        'class',
        'base_level',
        'job_level',
        'total_seconds',
        'achieved_at',
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class, 'char_id', 'char_id');
    }

    public function scopeEligible($query)
    {
        return $query->whereDoesntHave('character', function ($q) {
            $q->where('account_id', '>=', 2000010)
              ->orWhereHas('user', function ($u) {
                  $u->where('group_id', '>', 0);
              });
        })->where('name', 'not like', '%Test%');
    }

    public function getFormattedTimeAttribute(): string
    {
        $hours = floor($this->total_seconds / 3600);
        $minutes = floor(($this->total_seconds % 3600) / 60);
        $seconds = $this->total_seconds % 60;

        return sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
    }
}
