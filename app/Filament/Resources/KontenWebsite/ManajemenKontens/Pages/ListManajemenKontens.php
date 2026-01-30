<?php

namespace App\Filament\Resources\KontenWebsite\ManajemenKontens\Pages;

use App\Filament\Resources\KontenWebsite\ManajemenKontens\ManajemenKontenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManajemenKontens extends ListRecords
{
    protected static string $resource = ManajemenKontenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Buat Halaman Baru'),
        ];
    }
}
