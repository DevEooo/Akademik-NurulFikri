<?php

namespace App\Filament\Resources\Akademik\JadwalKuliahs\Schemas;

use Filament\Schemas\Schema;
use App\Models\TahunAjaran;
use App\Models\JadwalKuliah;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Closure;

class JadwalKuliahForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Akademik')
                    ->schema([
                        Select::make('id_tahun_ajaran')
                            ->relationship('tahunAjaran', 'tahun', fn ($query) => $query->orderBy('tahun')->orderBy('semester'))
                            ->getOptionLabelFromRecordUsing(fn ($record) => "Tahun: {$record->tahun} | Semester: {$record->semester}")
                            ->default(fn() => TahunAjaran::where('is_active', true)->first()?->id)
                            ->required(),

                        Select::make('id_mata_kuliah')
                            ->relationship('mataKuliah', 'nama_matkul')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_mk)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->label('Mata Kuliah'),

                        TextInput::make('kelas')
                            ->placeholder('Contoh: TI-2024-A')
                            ->required(),

                        TextInput::make('kuota_kelas')
                            ->numeric()
                            ->default(40)
                            ->label('Kuota Mahasiswa'),
                    ])->columns(2),

                Section::make('Waktu & Lokasi')
                    ->schema([
                        Select::make('id_dosen')
                            ->relationship('dosen', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Dosen Pengampu'),

                        Select::make('id_ruangan')
                            ->relationship('ruangan', 'nama')
                            ->required(),

                        Select::make('hari')
                            ->options([
                                'Senin' => 'Senin',
                                'Selasa' => 'Selasa',
                                'Rabu' => 'Rabu',
                                'Kamis' => 'Kamis',
                                'Jumat' => 'Jumat',
                                'Sabtu' => 'Sabtu',
                            ])
                            ->required(),

                        Grid::make(2)
                            ->schema([
                                TimePicker::make('jam_mulai')
                                    ->required()
                                    ->seconds(false)
                                    ->rules([
                                        function ($get) {
                                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                                if (!$get('id_ruangan') || !$get('hari') || !$get('jam_selesai')) return;

                                                $exists = JadwalKuliah::where('id_ruangan', $get('id_ruangan'))
                                                    ->where('hari', $get('hari'))
                                                    ->where('jam_mulai', '<', $get('jam_selesai'))
                                                    ->where('jam_selesai', '>', $get('jam_mulai'))
                                                    ->when($get('record'), fn($q) => $q->where('id', '!=', $get('record')->id))
                                                    ->exists();

                                                if ($exists) {
                                                    $fail('Ruangan ini sudah terpakai di jam tersebut!');
                                                }
                                            };
                                        }
                                    ]),
                                TimePicker::make('jam_selesai')
                                    ->required()
                                    ->seconds(false),
                            ]),
                    ])->columns(2),
            ]);
    }
}
