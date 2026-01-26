<?php

namespace App\Filament\Resources\MasterData\ProgramStudis\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class ProgramStudiForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Program Studi')
                    ->schema([
                        TextInput::make('kode_prodi')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Kode Prodi'),

                        Select::make('nama_prodi')
                            ->options([
                                'Sistem Informasi' => 'Sistem Informasi',
                                'Teknik Informatika' => 'Teknik Informatika',
                                'Bisnis Digital' => 'Bisnis Digital',
                            ])
                            ->required()
                            ->label('Nama Program Studi'),
                    ])->columns(2),
            ]);
    }
}
