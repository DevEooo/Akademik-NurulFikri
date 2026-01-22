<?php

namespace App\Filament\Resources\Akademik\Penilaians;

use App\Filament\Resources\Akademik\Penilaians\Pages\CreatePenilaian;
use App\Filament\Resources\Akademik\Penilaians\Pages\EditPenilaian;
use App\Filament\Resources\Akademik\Penilaians\Pages\ListPenilaians;
use App\Filament\Resources\Akademik\Penilaians\Schemas\PenilaianForm;
use App\Filament\Resources\Akademik\Penilaians\Tables\PenilaiansTable;
use App\Models\Penilaian;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PenilaianResource extends Resource
{
    protected static ?string $model = Penilaian::class;
    protected static UnitEnum|string|null $navigationGroup = 'Akademik';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PenilaianForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenilaiansTable::configure($table);
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
            'index' => ListPenilaians::route('/'),
            'create' => CreatePenilaian::route('/create'),
            'edit' => EditPenilaian::route('/{record}/edit'),
        ];
    }
}
