<?php

namespace App\Filament\Resources\Akademik\Penilaians\Pages;

use App\Filament\Resources\Akademik\Penilaians\PenilaianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenilaians extends ListRecords
{
    protected static string $resource = PenilaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Buat Penilaian Baru'),
        ];
    }
}
