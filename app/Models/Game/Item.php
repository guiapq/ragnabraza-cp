<?php

namespace App\Models\Game;

use App\Enums\Game\ItemTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item_db';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'type' => ItemTypeEnum::class,
    ];
}
