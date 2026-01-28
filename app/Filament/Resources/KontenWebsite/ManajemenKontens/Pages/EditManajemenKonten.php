<?php

namespace App\Filament\Resources\ManajemenKontens\Pages;

use App\Filament\Resources\ManajemenKontens\ManajemenKontenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManajemenKonten extends EditRecord
{
    protected static string $resource = ManajemenKontenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
