<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class EventMvpKill extends Model
{
    protected $table = 'event_mvp_kills';

    protected $fillable = [
        'char_id',
        'char_name',
        'mob_id',
        'mob_name',
        'killed_at',
    ];

    protected $casts = [
        'killed_at' => 'datetime',
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
        })->where('char_name', 'not like', '%Test%');
    }
}
