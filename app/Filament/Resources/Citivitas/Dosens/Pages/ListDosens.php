<?php

namespace App\Filament\Resources\Civitas\Dosens\Pages;

use App\Filament\Resources\Civitas\Dosens\DosenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDosens extends ListRecords
{
    protected static string $resource = DosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
