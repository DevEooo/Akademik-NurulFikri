<?php

namespace App\Filament\Resources\Akademik\KRS\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use App\Models\JadwalKuliah;
use App\Models\TahunAjaran;
use Filament\Forms\Components\Repeater;

class KRSForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->schema([
            Section::make('Informasi Akademik')
                ->schema([
                    Select::make('id_mahasiswa')
                        ->relationship('mahasiswa', 'nama_lengkap')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Mahasiswa'),

                    Select::make('id_tahun_ajaran')
                        ->options(TahunAjaran::where('is_active', true)->get()->pluck('nama', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->default(function () {
                            return TahunAjaran::where('is_active', true)->first()?->id;
                        })
                        ->label('Tahun Ajaran'),
                    
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'diajukan' => 'Diajukan',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                        ])
                        ->default('draft')
                        ->required(),
                ])->columns(3),

            Section::make('Daftar Mata Kuliah Diambil')
                ->schema([
                    Repeater::make('details')
                        ->relationship()
                        ->schema([
                            Select::make('id_jadwal_kuliah')
                                ->label('Pilih Jadwal Matkul')
                                ->options(function () {
                                    return JadwalKuliah::with(['mataKuliah', 'ruangan'])
                                        ->get()
                                        ->mapWithKeys(function ($jadwal) {
                                            $label = "{$jadwal->mataKuliah->nama_mk} - {$jadwal->hari}, {$jadwal->jam_mulai} ({$jadwal->ruangan->kode})";
                                            return [$jadwal->id => $label];
                                        });
                                })
                                ->searchable()
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->columnSpanFull(),
                        ])
                        ->addActionLabel('Tambah Mata Kuliah')
                        ->defaultItems(1)
                        ->grid(2)
                        ->collapsible(),
                ]),
        ]);
    }
}
