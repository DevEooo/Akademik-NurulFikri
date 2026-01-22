<?php

namespace App\Filament\Resources\Civitas\Dosens\Pages;

use App\Filament\Resources\Civitas\Dosens\DosenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDosen extends EditRecord
{
    protected static string $resource = DosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
