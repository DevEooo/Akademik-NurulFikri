<?php

namespace App\Filament\Resources\Akademik\JadwalKuliahs\Pages;

use App\Filament\Resources\Akademik\JadwalKuliahs\JadwalKuliahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalKuliah extends EditRecord
{
    protected static string $resource = JadwalKuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
