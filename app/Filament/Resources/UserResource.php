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
                        ->preload(),
                ]),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Пароль')
                    ->confirmed(),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->label('Подтвердите пароль'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("last_name")->label('Фамилия'),
                Tables\Columns\TextColumn::make("name")->label('Имя'),
                Tables\Columns\TextColumn::make("surname")->label('Отчество'),
                Tables\Columns\TextColumn::make('email')->label('Почта'),
                Tables\Columns\TextColumn::make('roles.name')->label('Роль'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
