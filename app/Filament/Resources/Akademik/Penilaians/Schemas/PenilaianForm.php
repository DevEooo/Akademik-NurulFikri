<?php

namespace App\Filament\Resources\Akademik\Penilaians\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Models\JadwalKuliah;
use App\Models\Penilaian;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\Mahasiswa;
class PenilaianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Mahasiswa')
                    ->schema([
                        Select::make('id_jadwal_kuliah')
                            ->label('Kelas / Mata Kuliah')
                            ->options(JadwalKuliah::with('mataKuliah')
                                ->get()
                                ->mapWithKeys(fn($j) => [$j->id => "{$j->mataKuliah->nama_mk} - {$j->kelas}"]))
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('id_mahasiswa', null)),

                        Select::make('id_mahasiswa')
                            ->label('Mahasiswa')
                            ->options(Mahasiswa::all()->pluck('nama_lengkap', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Section::make('Input Nilai')
                    ->description('Nilai Akhir akan dihitung otomatis (Bobot: 30-30-40)')
                    ->schema([
                        TextInput::make('nilai_tugas')
                            ->numeric()
                            ->maxValue(100)
                            ->default(0)
                            ->live(onBlur: true) 
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::hitungNilai($get, $set);
                            }),

                        // INPUT UTS
                        TextInput::make('nilai_uts')
                            ->numeric()
                            ->maxValue(100)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::hitungNilai($get, $set);
                            }),

                        // INPUT UAS
                        TextInput::make('nilai_uas')
                            ->numeric()
                            ->maxValue(100)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::hitungNilai($get, $set);
                            }),

                        // HASIL (Read Only)
                        TextInput::make('nilai_akhir')
                            ->label('Nilai Akhir')
                            ->readOnly()
                            ->numeric(),

                        TextInput::make('grade')
                            ->label('Grade Huruf')
                            ->readOnly(),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),
            ]);
    }

    public static function hitungNilai(Get $get, Set $set)
    {
        $tugas = floatval($get('nilai_tugas'));
        $uts   = floatval($get('nilai_uts'));
        $uas   = floatval($get('nilai_uas'));

        $akhir = ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);
        $set('nilai_akhir', number_format($akhir, 2));
        $set('grade', Penilaian::getGradeHuruf($akhir));
    }
}
