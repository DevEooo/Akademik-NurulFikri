<?php

namespace App\Filament\Resources\Akademik\Penilaians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class PenilaiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('jadwalKuliah.mataKuliah.nama_matkul')
                    ->label('Mata Kuliah')
                    ->collapsible(),
            ])
            ->defaultGroup('jadwalKuliah.mataKuliah.nama_matkul')
            ->columns([
                TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('mahasiswa.nama_lengkap')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nilai_tugas')->label('Tugas'),
                TextColumn::make('nilai_uts')->label('UTS'),
                TextColumn::make('nilai_uas')->label('UAS'),

                TextColumn::make('nilai_akhir')
                    ->label('Akhir')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('grade')
                    ->label('Grade')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'A', 'A-' => 'success',
                        'B+', 'B', 'B-' => 'info',
                        'C' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('id_jadwal_kuliah')
                    ->label('Filter Kelas')
                    ->relationship('jadwalKuliah.mataKuliah', 'nama_matkul'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    ExportBulkAction::make()
                        ->label('Export Nilai Terpilih')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename('Rekap_Nilai_Mahasiswa_' . date('Y-m-d'))
                                ->withColumns([

                                    Column::make('mahasiswa.nim')
                                        ->heading('NIM'),

                                    Column::make('mahasiswa.nama_lengkap')
                                        ->heading('Nama Mahasiswa'),

                                    // Pastikan 'nama_mk' atau 'nama_matkul' sesuai dengan database Anda
                                    Column::make('jadwalKuliah.mataKuliah.nama_mk')
                                        ->heading('Mata Kuliah'),

                                    Column::make('nilai_tugas')
                                        ->heading('Tugas'),

                                    Column::make('nilai_uts')
                                        ->heading('UTS'),

                                    Column::make('nilai_uas')
                                        ->heading('UAS'),

                                    Column::make('nilai_akhir')
                                        ->heading('Nilai Angka'),

                                    Column::make('grade')
                                        ->heading('Nilai Huruf'),
                                ]),
                        ]),
                ]),
            ]);
    }
}
