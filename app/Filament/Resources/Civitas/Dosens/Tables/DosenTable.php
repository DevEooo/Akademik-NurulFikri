<?php

namespace App\Filament\Resources\Civitas\Dosens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DosenTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nidn')
                    ->label('NIDN')
                    ->searchable(),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email'),

                TextColumn::make('nomor_telepon')
                    ->label('Nomor Telepon'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
