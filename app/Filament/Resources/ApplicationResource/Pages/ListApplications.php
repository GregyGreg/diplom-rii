<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Application;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getActions(): array
    {
        if (auth()->user()->hasRole('super_admin')) {
            return [
                Actions\Action::make('pdf')
                    ->label('PDF')
//                    ->action()
                    ->icon('heroicon-o-download')
                    ->button(),
                Actions\Action::make('excel')
                    ->label('Excel')
                    ->url(route('export_excel'))
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
        return [10, 25, 50];
    }
}
