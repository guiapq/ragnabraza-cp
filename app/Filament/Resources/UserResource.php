<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Game Server';

    protected static ?string $modelLabel = 'Player Account';

    protected static ?string $pluralModelLabel = 'Player Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('userid')
                    ->label('Username')
                    ->required()
                    ->maxLength(23),
                Forms\Components\TextInput::make('user_pass')
                    ->label('Password (Hash/Raw)')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? md5($state) : null)
                    ->dehydrated(fn ($state) => filled($state)),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(39),
                Forms\Components\Select::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('group_id')
                    ->label('GM Group ID (0=Player, 99=Admin)')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('state')
                    ->label('Account State')
                    ->options([
                        0 => 'Active (Normal)',
                        5 => 'Banned',
                    ])
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('userid')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sex')
                    ->badge()
                    ->colors([
                        'info' => 'M',
                        'danger' => 'F',
                    ]),
                Tables\Columns\TextColumn::make('group_id')
                    ->label('GM Level')
                    ->badge()
                    ->colors([
                        'success' => fn ($state): bool => $state >= 1,
                        'gray' => fn ($state): bool => $state == 0,
                    ]),
                Tables\Columns\TextColumn::make('state')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state == 0 ? 'Active' : 'Banned')
                    ->colors([
                        'success' => 0,
                        'danger' => 5,
                    ]),
                Tables\Columns\TextColumn::make('lastlogin')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
