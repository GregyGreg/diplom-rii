<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $label = 'Заявка';

    protected static ?string $pluralLabel = 'Заявки';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        $executorField = Forms\Components\Select::make('executor_id')
            ->label('Исполнитель')
            ->searchable()
            ->relationship('executors', 'last_name');

        $statusField = Forms\Components\Select::make('status')
            ->label('Статус заявки')
            ->options([
                'in_progress' => 'В процессе',
                'done' => 'Выполнено',
                'fall' => 'Не выполнено',
            ])
            ->default('in_progress');

        $causeFallField = Forms\Components\Textarea::make('cause_fall')
            ->label('Причина невыполнения');

        $closeApplication = Forms\Components\DateTimePicker::make('close_application')
            ->label('Дата обработки заявки');

        if (!auth()->user()->hasRole('super_admin')) {
            $executorField->hiddenOn(['create', 'edit']);
            $statusField->hiddenOn(['create', 'edit']);
            $causeFallField->hiddenOn(['create', 'edit']);
            $closeApplication->hiddenOn(['create', 'edit']);
        }


        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Select::make('author_id')
                        ->label('Заявитель')
                        ->relationship('authors', 'last_name')
                        ->default(Auth::id())
                        ->disabled(),
                    Forms\Components\Textarea::make('text_application')
                        ->label('Текст заявки'),
                    Forms\Components\DateTimePicker::make('create_application')
                        ->label('Дата создания заявки')
                        ->default(Carbon::now())
                        ->disabled(),
                ]),
                Forms\Components\Group::make()->schema([
                    $executorField,
                    $statusField,
                ]),
                $causeFallField,
                $closeApplication,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggledHiddenByDefault(true),
                Tables\Columns\TextColumn::make('authors.last_name')
                    ->label('Заявитель'),
                Tables\Columns\TextColumn::make('executors.last_name')
                    ->default('Не выбран')
                    ->label('Исполнитель'),
                Tables\Columns\TextColumn::make('status')
                    ->enum([
                        'in_progress' => 'В процессе',
                        'done' => 'Выполнено',
                        'fall' => 'Не выполнено',
                    ])
                    ->label('Статус заявки'),
                Tables\Columns\TextColumn::make('create_application')
                    ->label('Дата создания заявки'),
                Tables\Columns\TextColumn::make('close_application')
                    ->label('Дата обработки заявки'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->multiple()
                    ->options([
                        'in_progress' => 'В процессе',
                        'done' => 'Выполнено',
                        'fall' => 'Не выполнено',
                    ]),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Дата с'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Дата по'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('create_application', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('create_application', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->hidden(!auth()->user()->hasRole('super_admin')),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(!auth()->user()->hasRole('super_admin')),
                ])])
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
            'view' => Pages\ViewApplications::route('/{record}/view'),
        ];
    }
}
