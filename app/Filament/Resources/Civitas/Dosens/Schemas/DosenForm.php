<?php

namespace App\Filament\Resources\Civitas\Dosens\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class DosenForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Dosen')
                    ->schema([
                        TextInput::make('nidn')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('NIDN'),

                        TextInput::make('nama_lengkap')
                            ->required()
                            ->label('Nama Lengkap'),

                        TextInput::make('gelar_depan')
                            ->required()
                            ->label('Gelar Depan'),

                        TextInput::make('gelar_belakang')
                            ->label('Gelar Belakang'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Email'),

                        TextInput::make('nomor_telepon')
                            ->required()
                            ->label('Nomor Telepon'),

                        TextInput::make('alamat')
                            ->required()
                            ->label('Alamat'),
                    ])->columns(2),
            ]);
    }
}
