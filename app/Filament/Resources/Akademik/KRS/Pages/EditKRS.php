<?php

namespace App\Filament\Resources\Akademik\KRS\Pages;

use App\Filament\Resources\Akademik\KRS\KRSResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKRS extends EditRecord
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
