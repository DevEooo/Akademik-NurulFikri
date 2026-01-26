<?php

namespace App\Filament\Resources\Akademik\JadwalKuliahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use App\Models\TahunAjaran;
class JadwalKuliahsTable
{
    public static function table(Table $table): Table
{
    return $table
        ->defaultGroup('hari') 
        ->groups([
            Group::make('hari')
                ->collapsible(),
        ])
        ->columns([
            TextColumn::make('jam_mulai')
                ->label('Waktu')
                ->formatStateUsing(fn ($record) => "{$record->jam_mulai} - {$record->jam_selesai}")
                ->sortable(),

            TextColumn::make('mataKuliah.nama_mk')
                ->label('Mata Kuliah')
                ->description(fn ($record) => $record->mataKuliah->kode_mk . ' (' . $record->mataKuliah->sks . ' SKS)')
                ->searchable(),

            TextColumn::make('kelas')
                ->badge() 
                ->color('info')
                ->searchable(),

            TextColumn::make('dosen.nama_lengkap')
                ->label('Dosen')
                ->limit(20), 

            TextColumn::make('ruangan.nama')
                ->label('Ruang')
                ->icon('heroicon-o-map-pin'),
        ])
        ->filters([
            SelectFilter::make('id_tahun_ajaran')
                ->relationship('tahunAjaran', 'tahun', fn ($query) => $query->orderBy('tahun')->orderBy('semester'))
                ->getOptionLabelFromRecordUsing(fn ($record) => "Tahun: {$record->tahun} | Semester: {$record->semester}")
                ->default(fn () => TahunAjaran::where('is_active', true)->first()?->id),
                
            SelectFilter::make('id_ruangan')
                ->relationship('ruangan', 'nama')
                ->label('Filter Ruangan'),
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
