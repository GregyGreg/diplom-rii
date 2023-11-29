<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        if ($this->getRecord()->id == Auth::id()) {

            return url(env('FILAMENT_PATH') . '/login');
        } else {
            return $this->getResource()::getUrl('index');
        }
    }

    protected function getActions(): array
    {
        if ($this->getRecord()->id == Auth::id() or auth()->user()->hasRole('super_admin')) {
            return [
                Actions\DeleteAction::make(),
            ];
        } else {
            return [];
        }
    }
}
