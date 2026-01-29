<?php

namespace App\Filament\Resources\KontenWebsite\ManajemenKontens\Tables;

use App\Models\ManajemenKonten;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                    ->description(fn (ManajemenKonten $record) => $record->parent ? 'Sub-halaman dari: ' . $record->parent->title : 'Halaman Utama'),

                TextColumn::make('slug')
                    ->badge()
                    ->color('gray'),

                IconColumn::make('is_published')
                    ->boolean(),
            ])
            ->defaultSort('parent_id')
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Filter Parent')
                    ->relationship('parent', 'title'),
            ]);
    }
}
