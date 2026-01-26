<?php

namespace App\Filament\Resources\MasterData\ProgramStudis\Pages;

use App\Filament\Resources\MasterData\ProgramStudis\ProgramStudiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramStudi extends ListRecords
{
    protected static string $resource = ProgramStudiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
