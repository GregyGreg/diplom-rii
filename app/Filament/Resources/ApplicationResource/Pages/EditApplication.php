<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected static ?string $title = 'Редактирование заявки';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['close_application'] = Carbon::now();

        return parent::mutateFormDataBeforeFill($data);
    }

    protected function getActions(): array
    {
        if ($this->getRecord()->author_id == Auth::id() or auth()->user()->hasRole('super_admin')) {
            return [
                Actions\ViewAction::make(),
                Actions\DeleteAction::make(),
            ];
        } else {
            return [];
        }
    }
}
