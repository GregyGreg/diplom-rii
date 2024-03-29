<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'Пользователь';

    protected static ?string $pluralLabel = 'Пользователи';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'admin';

    public static function form(Form $form): Form
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $is_disabled = true;
        } else {
            $is_disabled = false;
        }
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('last_name')
                        ->label('Фамилия'),
                    Forms\Components\TextInput::make('name')
                        ->label('Имя'),
                    Forms\Components\TextInput::make('surname')
                        ->label('Отчество'),
                    Forms\Components\TextInput::make('email')
                        ->label('Почта'),
                    Forms\Components\TextInput::make('phone')
                        ->label('Номер телефона')
                        ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask->pattern('+{7} (000) 000-00-00')),
                    Forms\Components\Select::make('roleId')
                        ->label('Права')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->disabled($is_disabled)
                        ->preload(),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->autocomplete('password')
                        ->label('Пароль'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $filters = Tables\Filters\SelectFilter::make('id')
                ->default(Auth::id())
                ->visible();
        } else {
            $filters = Tables\Filters\SelectFilter::make('id')
                ->hidden();
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label('ID'),
                Tables\Columns\TextColumn::make("last_name")->label('Фамилия'),
                Tables\Columns\TextColumn::make("name")->label('Имя'),
                Tables\Columns\TextColumn::make("surname")->label('Отчество'),
                Tables\Columns\TextColumn::make('email')->label('Почта'),
                Tables\Columns\TextColumn::make('roles.name')->label('Роль'),
            ])
            ->defaultSort('id')
            ->filters([
                $filters,
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(!auth()->user()->hasRole('super_admin') ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
