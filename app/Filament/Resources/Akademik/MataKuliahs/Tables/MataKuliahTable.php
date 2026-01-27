<?php

namespace App\Filament\Resources\Akademik\MataKuliahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class MataKuliahTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_matkul')
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make('nama_matkul')
                    ->label('Nama Mata Kuliah')
                    ->searchable(),

                TextColumn::make('programStudi.nama_prodi')
                    ->label('Program Studi'),

                TextColumn::make('sks')
                    ->label('SKS')
                    ->numeric(),

                TextColumn::make('deskripsi_matkul')
                    ->label('Deskripsi')
                    ->limit(50),
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
