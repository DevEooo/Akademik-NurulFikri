<?php

namespace App\Filament\Resources\Akademik\KSRS;

use App\Filament\Resources\Akademik\KSRS\Pages\CreateKSR;
use App\Filament\Resources\Akademik\KSRS\Pages\EditKSR;
use App\Filament\Resources\Akademik\KSRS\Pages\ListKSRS;
use App\Filament\Resources\Akademik\KSRS\Schemas\KSRForm;
use App\Filament\Resources\Akademik\KSRS\Tables\KSRSTable;
use App\Models\KSR;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KSRResource extends Resource
{
    // protected static ?string $model = KSR::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Kartu Rencana Studi (KRS)';
    protected static string | UnitEnum | null $navigationGroup = 'Akademik';
    public static function form(Schema $schema): Schema
    {
        return KSRForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KSRSTable::configure($table);
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
            'index' => ListKSRS::route('/'),
            'create' => CreateKSR::route('/create'),
            'edit' => EditKSR::route('/{record}/edit'),
        ];
    }
}
