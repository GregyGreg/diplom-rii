<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use function auth;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Просмотр заявки';

    protected function getActions(): array
    {
        if ($this->getRecord()->id == Auth::id() or auth()->user()->hasRole('super_admin')) {
            return [
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ];
        } else {
           return [];
        }
    }
}
