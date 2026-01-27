<?php

namespace App\Filament\Resources\Akademik\KRS\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
class KRSTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('mahasiswa.nim')
                ->label('NIM')
                ->searchable()
                ->sortable(),

            TextColumn::make('mahasiswa.nama_lengkap')
                ->label('Nama Mahasiswa')
                ->searchable()
                ->weight('bold'),

            TextColumn::make('tahunAjaran.nama')
                ->label('Semester')
                ->sortable(),

            TextColumn::make('detail_count')
                ->counts('details')
                ->label('Jml Matkul')
                ->alignCenter(),
            
            TextColumn::make('total_sks')
                ->label('Total SKS')
                ->getStateUsing(function (Model $record) {
                    return $record->total_sks . ' SKS';
                })
                ->badge()
                ->color('info'),

            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'draft' => 'gray',
                    'diajukan' => 'warning',
                    'disetujui' => 'success',
                    'ditolak' => 'danger',
                }),

            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('id_tahun_ajaran')
                ->options(\App\Models\TahunAjaran::all()->pluck('nama', 'id'))
                ->label('Filter Semester')
                ->searchable(),
            
            SelectFilter::make('status')
                ->options([
                    'draft' => 'Draft',
                    'diajukan' => 'Diajukan',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                ]),
        ])
        ->actions([
            ViewAction::make(),
            EditAction::make(),
            Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn (Model $record) => $record->update(['status' => 'disetujui']))
                ->visible(fn (Model $record) => $record->status === 'diajukan'), 
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}
