<?php

namespace App\Filament\Resources\Akademik\MataKuliahs\Pages;

use App\Filament\Resources\Akademik\MataKuliahs\MataKuliahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMataKuliahs extends ListRecords
{
    protected static string $resource = MataKuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
