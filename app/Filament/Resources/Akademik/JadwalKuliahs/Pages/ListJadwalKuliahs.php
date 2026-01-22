<?php

namespace App\Filament\Resources\Akademik\JadwalKuliahs\Pages;

use App\Filament\Resources\Akademik\JadwalKuliahs\JadwalKuliahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalKuliahs extends ListRecords
{
    protected static string $resource = JadwalKuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
