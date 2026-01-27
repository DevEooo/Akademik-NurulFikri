<?php

namespace App\Filament\Resources\Civitas\Staff\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class StaffForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Staff')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->label('User Account'),

                        TextInput::make('nip')
                            ->unique(ignoreRecord: true)
                            ->label('NIP'),

                        TextInput::make('nama')
                            ->required()
                            ->label('Nama'),

                        Select::make('jabatan')
                            ->options([
                                'BAAK' => 'BAAK',
                                'Keuangan' => 'Keuangan',
                                'IT' => 'IT',
                            ])
                            ->required()
                            ->label('Jabatan'),
                    ])->columns(2),
            ]);
    }
}
