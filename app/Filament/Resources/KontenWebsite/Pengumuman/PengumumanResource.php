<?php

namespace App\Filament\Resources\KontenWebsite\Pengumuman;

use App\Filament\Resources\KontenWebsite\Pengumuman\Pages\CreatePengumuman;
use App\Filament\Resources\KontenWebsite\Pengumuman\Pages\EditPengumuman;
use App\Filament\Resources\KontenWebsite\Pengumuman\Pages\ListPengumuman;
use App\Filament\Resources\KontenWebsite\Pengumuman\Schemas\PengumumanForm;
use App\Filament\Resources\KontenWebsite\Pengumuman\Tables\PengumumanTable;
use App\Models\Pengumuman;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengumumanResource extends Resource
{
    // protected static ?string $model = Pengumuman::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | UnitEnum | null $navigationGroup = 'Konten Website';
    public static function form(Schema $schema): Schema
    {
        return PengumumanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengumumanTable::configure($table);
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
            'index' => ListPengumuman::route('/'),
            'create' => CreatePengumuman::route('/create'),
            'edit' => EditPengumuman::route('/{record}/edit'),
        ];
    }
}
