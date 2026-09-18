<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacterResource\Pages;
use App\Models\Game\Character;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Game Server';

    protected static ?string $modelLabel = 'Character';

    protected static ?string $pluralModelLabel = 'Characters';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('account_id')
                    ->label('Account ID')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('base_level')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('job_level')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('zeny')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('last_map')
                    ->label('Current/Last Map')
                    ->default('prontera'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('char_id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Character Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.userid')
                    ->label('Account')
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_level')
                    ->label('Base Lv')
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_level')
                    ->label('Job Lv')
                    ->sortable(),
                Tables\Columns\TextColumn::make('zeny')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state) . ' Z')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_map')
                    ->label('Map')
                    ->searchable(),
                Tables\Columns\IconColumn::make('online')
                    ->boolean()
                    ->label('Online'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('reset_position')
                    ->label('Reset Map')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Character Position')
                    ->modalDescription('Reset character spawn location to Prontera (156, 191)? Use this if player is stuck.')
                    ->action(function (Character $record) {
                        $record->update([
                            'last_map' => 'prontera',
                            'last_x' => 156,
                            'last_y' => 191,
                        ]);

                        Notification::make()
                            ->title("Character {$record->name} teleported to Prontera")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCharacters::route('/'),
            'edit' => Pages\EditCharacter::route('/{record}/edit'),
        ];
    }
}
