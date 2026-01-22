<?php

namespace App\Filament\Resources\Akademik\KSRS\Pages;

use App\Filament\Resources\Akademik\KSRS\KSRResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKSR extends EditRecord
{
    protected static string $resource = KSRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
