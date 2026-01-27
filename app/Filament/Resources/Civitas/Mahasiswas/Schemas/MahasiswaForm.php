<?php

namespace App\Filament\Resources\Civitas\Mahasiswas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class MahasiswaForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pribadi')
                    ->schema([
                        TextInput::make('nim')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('NIM'),

                        TextInput::make('nama_lengkap')
                            ->required()
                            ->label('Nama Lengkap'),

                        TextInput::make('angkatan')
                            ->required()
                            ->label('Angkatan'),
                    ])->columns(2),

                Section::make('Informasi Akademik')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'lulus' => 'Lulus',
                                'dropout' => 'Dropout',
                            ])
                            ->required()
                            ->label('Status'),

                        Select::make('program_studi_id')
                            ->relationship('programStudi', 'nama_prodi')
                            ->required()
                            ->label('Program Studi'),

                        Select::make('dosen_wali_id')
                            ->relationship('dosenWali', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->label('Dosen Wali'),
                    ])->columns(2),
            ]);
    }
}
