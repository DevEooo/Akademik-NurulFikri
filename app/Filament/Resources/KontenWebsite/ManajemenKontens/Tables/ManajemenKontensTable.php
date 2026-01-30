<?php

namespace App\Filament\Resources\KontenWebsite\ManajemenKontens\Tables;

use App\Models\ManajemenKonten;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;

class ManajemenKontensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Halaman')
                    ->searchable()
                    ->alignment('center')
                    ->description(fn (ManajemenKonten $record) => $record->parent ? 'Sub-halaman dari: ' . $record->parent->title : 'Halaman Utama'),

                TextColumn::make('slug')
                    ->badge()
                    ->alignment('center')
                    ->color('gray'),

                IconColumn::make('is_published')
                    ->boolean()
                    ->alignment('center'),
            ])
            ->defaultSort('id_parent')
            ->filters([
                SelectFilter::make('id_parent')
                    ->label('Filter Parent')
                    ->relationship('parent', 'title'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
