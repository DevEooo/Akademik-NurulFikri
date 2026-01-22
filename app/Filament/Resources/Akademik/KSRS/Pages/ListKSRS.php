<?php

namespace App\Filament\Resources\Akademik\KSRS\Pages;

use App\Filament\Resources\Akademik\KSRS\KSRResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKSRS extends ListRecords
{
    protected static string $resource = KSRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
