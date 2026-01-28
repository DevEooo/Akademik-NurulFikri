<?php

namespace App\Filament\Resources\ManajemenKontens\Pages;

use App\Filament\Resources\ManajemenKontens\ManajemenKontenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManajemenKontens extends ListRecords
{
    protected static string $resource = ManajemenKontenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
