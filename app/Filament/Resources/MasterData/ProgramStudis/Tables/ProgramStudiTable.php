<?php

namespace App\Filament\Resources\MasterData\ProgramStudis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ProgramStudiTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_prodi')
                    ->label('Kode Prodi')
                    ->searchable(),

                TextColumn::make('nama_prodi')
                    ->label('Nama Program Studi')
                    ->badge(),
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
