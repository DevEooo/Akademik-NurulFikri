<?php

namespace App\Filament\Resources\MasterData\TahunAjarans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class TahunAjaranForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Tahun Ajaran')
                    ->schema([
                        TextInput::make('tahun')
                            ->required()
                            ->label('Tahun'),

                        Select::make('semester')
                            ->options([
                                'Ganjil' => 'Ganjil',
                                'Genap' => 'Genap',
                            ])
                            ->required()
                            ->label('Semester'),

                        Toggle::make('is_active')
                            ->label('Aktif'),
                    ])->columns(3),
            ]);
    }
}
