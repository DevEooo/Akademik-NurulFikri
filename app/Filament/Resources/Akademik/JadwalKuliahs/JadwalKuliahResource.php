<?php

namespace App\Filament\Resources\Akademik\JadwalKuliahs;

use App\Filament\Resources\Akademik\JadwalKuliahs\Pages\CreateJadwalKuliah;
use App\Filament\Resources\Akademik\JadwalKuliahs\Pages\EditJadwalKuliah;
use App\Filament\Resources\Akademik\JadwalKuliahs\Pages\ListJadwalKuliahs;
use App\Models\JadwalKuliah;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class JadwalKuliahResource extends Resource
{
    protected static ?string $model = JadwalKuliah::class;
    protected static UnitEnum|string|null $navigationGroup = 'Akademik';
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            //
        ])->filters([
            //
        ])->actions([
            //
        ])->bulkActions([
            //
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJadwalKuliahs::route('/'),
            'create' => CreateJadwalKuliah::route('/create'),
            'edit' => EditJadwalKuliah::route('/{record}/edit'),
        ];
    }
}
