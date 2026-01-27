<?php

namespace App\Filament\Resources\Akademik\MataKuliahs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class MataKuliahForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Mata Kuliah')
                    ->schema([
                        Select::make('id_program_studi')
                            ->relationship('programStudi', 'nama_prodi')
                            ->required()
                            ->label('Program Studi'),

                        TextInput::make('kode_matkul')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Kode Mata Kuliah'),

                        TextInput::make('nama_matkul')
                            ->required()
                            ->label('Nama Mata Kuliah'),

                        TextInput::make('sks')
                            ->numeric()
                            ->required()
                            ->label('SKS'),

                        Textarea::make('deskripsi_matkul')
                            ->label('Deskripsi Mata Kuliah'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
