<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        if (auth()->user()->hasRole('super_admin')) {
            return [
                Actions\Action::make('excel')
                    ->label('Excel')
                    ->url(route('user_export_excel'))
                    ->icon('heroicon-o-download')
                    ->button(),
                Actions\CreateAction::make(),
            ];
        } else {
            return [
                Actions\CreateAction::make()
            ];
        }
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }
}
