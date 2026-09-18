<?php

namespace App\Filament\Pages;

use App\Models\Game\EventMvpKill;
use App\Models\Game\EventSpeedrun;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventTournaments extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Event & Competitions';

    protected static ?string $title = 'Torneios de Evento & Leaderboard';

    protected static string $view = 'filament.pages.event-tournaments';

    public function getActiveSeed(): string
    {
        $randoFile = base_path('../.env.rando');
        if (file_exists($randoFile)) {
            $content = file_get_contents($randoFile);
            if (preg_match('/^WORLD_SEED=(.*)$/m', $content, $matches)) {
                return trim($matches[1]);
            }
        }
        return 'default';
    }

    public function getSpeedruns(): Collection
    {
        try {
            return EventSpeedrun::orderBy('total_seconds', 'asc')->take(20)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function getMvpBounties(): Collection
    {
        try {
            return EventMvpKill::select(
                'char_id',
                'char_name',
                DB::raw('COUNT(*) as total_kills'),
                DB::raw('COUNT(DISTINCT mob_id) as distinct_mvps'),
                DB::raw('MAX(killed_at) as last_kill')
            )
            ->groupBy('char_id', 'char_name')
            ->orderByDesc('total_kills')
            ->take(20)
            ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function getRecentKills(): Collection
    {
        try {
            return EventMvpKill::orderByDesc('killed_at')->take(10)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('openKiosk')
                ->label('Abrir Modo Telão (Kiosk)')
                ->icon('heroicon-o-tv')
                ->color('success')
                ->url('/scoreboard', shouldOpenInNewTab: true),

            Action::make('resetEventSeason')
                ->label('Zerar Rodada / Novo Dia')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Zerar Rodada do Evento?')
                ->modalDescription('Isso irá apagar os registros atuais de Speedrun e MVP Kills para iniciar um novo dia ou torneio.')
                ->action(function () {
                    try {
                        EventSpeedrun::truncate();
                        EventMvpKill::truncate();
                        Notification::make()
                            ->title('Rodada resetada com sucesso!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Erro ao resetar: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
