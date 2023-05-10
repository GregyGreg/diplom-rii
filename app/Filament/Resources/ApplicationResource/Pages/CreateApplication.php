<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;

    protected static ?string $title = 'Создание заявки';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
