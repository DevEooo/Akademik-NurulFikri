<?php

namespace App\Filament\Resources\Akademik\JadwalKuliahs;

use App\Filament\Resources\Akademik\JadwalKuliahs\Pages\CreateJadwalKuliah;
use App\Filament\Resources\Akademik\JadwalKuliahs\Pages\EditJadwalKuliah;
use App\Filament\Resources\Akademik\JadwalKuliahs\Pages\ListJadwalKuliahs;
use App\Filament\Resources\Akademik\JadwalKuliahs\Schemas\JadwalKuliahForm;
use App\Filament\Resources\Akademik\JadwalKuliahs\Tables\JadwalKuliahsTable;
use App\Models\JadwalKuliah;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class JadwalKuliahResource extends Resource
{
    protected static ?string $model = JadwalKuliah::class;
    protected static UnitEnum|string|null $navigationGroup = 'Akademik';
    protected static ?string $label = "Jadwal Kuliah";
    protected static ?string $slug = "jadwal-kuliah";
    protected static BackedEnum|string|null $navigationIcon = Heroicon::CalendarDateRange;

    public static function form(Schema $schema): Schema
    {
        return JadwalKuliahForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalKuliahsTable::table($table);
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

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:JadwalKuliah') ?? false;
    }
}
