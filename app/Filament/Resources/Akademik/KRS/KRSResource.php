<?php

namespace App\Filament\Resources\Akademik\KRS;

use App\Filament\Resources\Akademik\KRS\Pages\CreateKRS;
use App\Filament\Resources\Akademik\KRS\Pages\EditKRS;
use App\Filament\Resources\Akademik\KRS\Pages\ListKRS;
use App\Filament\Resources\Akademik\KRS\Schemas\KRSForm;
use App\Filament\Resources\Akademik\KRS\Tables\KRSTable;
use App\Models\KRS;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KRSResource extends Resource
{
    protected static ?string $model = KRS::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Kartu Rencana Studi (KRS)';
    protected static string | UnitEnum | null $navigationGroup = 'Akademik';
    public static function form(Schema $schema): Schema
    {
        return KRSForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KRSTable::configure($table);
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
            'index' => ListKRS::route('/'),
            'create' => CreateKRS::route('/create'),
            'edit' => EditKRS::route('/{record}/edit'),
        ];
    }
}
