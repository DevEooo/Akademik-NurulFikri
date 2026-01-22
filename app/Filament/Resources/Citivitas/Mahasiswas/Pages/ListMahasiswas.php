<?php

namespace App\Filament\Resources\Civitas\Mahasiswas\Pages;

use App\Filament\Resources\Civitas\Mahasiswas\MahasiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMahasiswas extends ListRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
