<?php

namespace App\Filament\Pages;

use App\Services\WorldDataService;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class WorldDatabase extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'World Generation';

    protected static ?string $title = 'Procedural World Explorer';

    protected static string $view = 'filament.pages.world-database';

    public string $activeTab = 'mobs'; // 'mobs', 'items', 'meta', 'routes'
    public string $search = '';
    public string $selectedRace = 'all';
    public string $selectedElement = 'all';
    public string $selectedTier = 'Lv 1–25';
    public ?int $viewMobId = null;
    public ?int $viewItemId = null;

    public function mount(): void
    {
        $this->activeTab = 'mobs';
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->search = '';
        $this->viewMobId = null;
        $this->viewItemId = null;
    }

    public function selectMob(?int $id): void
    {
        $this->viewMobId = $id;
    }

    public function selectItem(?int $id): void
    {
        $this->viewItemId = $id;
    }

    public function getWorldData(): WorldDataService
    {
        return app(WorldDataService::class);
    }

    public function getActiveSeed(): string
    {
        return $this->getWorldData()->getActiveSeed();
    }

    public function getMobs(): Collection
    {
        $allMobs = $this->getWorldData()->getMobs();
        $spawns = $this->getWorldData()->getMobSpawns()['mob_to_maps'] ?? [];
        $mobs = [];

        foreach ($allMobs as $mob) {
            if ($this->search !== '') {
                $s = strtolower($this->search);
                if (
                    !str_contains(strtolower($mob['name']), $s) &&
                    !str_contains((string)$mob['id'], $s)
                ) {
                    continue;
                }
            }

            if ($this->selectedRace !== 'all' && (string)$mob['race_id'] !== $this->selectedRace) {
                continue;
            }

            $mobData = $mob;
            $mobData['maps'] = $spawns[$mob['id']] ?? [];
            $mobs[] = $mobData;

            if (count($mobs) >= 60) {
                break;
            }
        }

        return collect($mobs);
    }

    public function getItems(): Collection
    {
        $allItems = $this->getWorldData()->getItems();
        $itemToMobs = $this->getWorldData()->getItemToMobs();
        $shops = $this->getWorldData()->getNpcShops()['item_to_shops'] ?? [];

        $items = [];

        foreach ($allItems as $item) {
            if ($this->search !== '') {
                $s = strtolower($this->search);
                if (
                    !str_contains(strtolower($item['name']), $s) &&
                    !str_contains(strtolower($item['aegis']), $s) &&
                    !str_contains((string)$item['id'], $s)
                ) {
                    continue;
                }
            }

            $itemData = $item;
            $itemData['dropped_by_count'] = count($itemToMobs[$item['id']] ?? []);
            $itemData['shops_count'] = count($shops[$item['id']] ?? []);
            $items[] = $itemData;

            if (count($items) >= 60) {
                break;
            }
        }

        return collect($items);
    }

    public function getSelectedMob(): ?array
    {
        if (!$this->viewMobId) {
            return null;
        }

        $all = $this->getWorldData()->getMobs();
        if (!isset($all[$this->viewMobId])) {
            return null;
        }

        $mob = $all[$this->viewMobId];
        $spawns = $this->getWorldData()->getMobSpawns()['mob_to_maps'] ?? [];
        $mob['maps'] = $spawns[$this->viewMobId] ?? [];

        return $mob;
    }

    public function getSelectedItem(): ?array
    {
        if (!$this->viewItemId) {
            return null;
        }

        $all = $this->getWorldData()->getItems();
        if (!isset($all[$this->viewItemId])) {
            return null;
        }

        $item = $all[$this->viewItemId];
        $itemToMobs = $this->getWorldData()->getItemToMobs();
        $shops = $this->getWorldData()->getNpcShops()['item_to_shops'] ?? [];

        $item['dropped_by'] = $itemToMobs[$this->viewItemId] ?? [];
        $item['shops'] = $shops[$this->viewItemId] ?? [];

        return $item;
    }

    public function getMetaAnalysis(): array
    {
        return $this->getWorldData()->getMetaAnalysis();
    }

    public function getExpRoutes(): array
    {
        return $this->getWorldData()->getExpRoutes();
    }
}
