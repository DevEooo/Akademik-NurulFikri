<?php

namespace App\Filament\Resources\MasterData\Ruangans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class RuanganForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Ruangan')
                    ->schema([
                        TextInput::make('nama')
                            ->required()
                            ->label('Nama Ruangan'),

                        TextInput::make('kapasitas')
                            ->numeric()
                            ->required()
                            ->label('Kapasitas'),
                    ])->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
