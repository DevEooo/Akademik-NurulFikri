<?php

namespace App\Filament\Resources\Akademik\KRS\Pages;

use App\Filament\Resources\Akademik\KRS\KRSResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKRS extends ListRecords
{
    protected static string $resource = KRSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label("Buat KRS Baru"),
        ];
    }
}
