<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class Mob extends Model
{
    protected $table = 'mob_db';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];

    /**
     * Retorna lista de drops formatada com id e percentual (100 = 1%).
     *
     * @return array<int, array{id: int, rate: float}>
     */
    public function getDropsAttribute(): array
    {
        $drops = [];
        for ($i = 1; $i <= 9; $i++) {
            $dropId = $this->attributes["Drop{$i}id"] ?? 0;
            $dropPer = $this->attributes["Drop{$i}per"] ?? 0;
            if ($dropId > 0 && $dropPer > 0) {
                $drops[] = [
                    'id'   => $dropId,
                    'rate' => $dropPer / 100.0,
                ];
            }
        }

        $cardId = $this->attributes['DropCardid'] ?? 0;
        $cardPer = $this->attributes['DropCardper'] ?? 0;
        if ($cardId > 0 && $cardPer > 0) {
            $drops[] = [
                'id'   => $cardId,
                'rate' => $cardPer / 100.0,
                'is_card' => true,
            ];
        }

        return $drops;
    }

    /**
     * Retorna os itens de MVP (se o monstro for MVP).
     *
     * @return array<int, array{id: int, rate: float}>
     */
    public function getMvpDropsAttribute(): array
    {
        $mvpDrops = [];
        for ($i = 1; $i <= 3; $i++) {
            $mvpId = $this->attributes["MVP{$i}id"] ?? 0;
            $mvpPer = $this->attributes["MVP{$i}per"] ?? 0;
            if ($mvpId > 0 && $mvpPer > 0) {
                $mvpDrops[] = [
                    'id'   => $mvpId,
                    'rate' => $mvpPer / 100.0,
                ];
            }
        }
        return $mvpDrops;
    }
}
