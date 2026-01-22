<?php

namespace App\Filament\Resources\Akademik\MataKuliahs\Pages;

use App\Filament\Resources\Akademik\MataKuliahs\MataKuliahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMataKuliah extends EditRecord
{
    protected static string $resource = MataKuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
