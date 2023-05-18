<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewApplications extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    protected static ?string $title = 'Просмотр заявки';

    protected function getActions(): array
    {
        if ($this->getRecord()->author_id == Auth::id() or auth()->user()->hasRole('super_admin')) {
            return [
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ];
        } else {
           return [];
        }
    }
}
