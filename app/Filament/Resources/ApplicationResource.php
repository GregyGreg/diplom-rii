<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $label = 'Заявка';

    protected static ?string $pluralLabel = 'Заявки';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Select::make('author_id')
                        ->label('Заявитель')
                        ->relationship('authors', 'last_name')
                        ->default(Auth::id())
                        ->disabled(),
                    Forms\Components\Textarea::make('text_application')
                        ->label('Текст заявки'),
                    Forms\Components\Select::make('executor_id')
                        ->label('Исполнитель')
                        ->relationship('executors', 'last_name'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('authors.last_name')->label('Имя заявителя'),
                Tables\Columns\TextColumn::make('executors.name')->label('Имя исполнителя'),
                Tables\Columns\TextColumn::make('created_at')->label('Дата создания заявки'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
