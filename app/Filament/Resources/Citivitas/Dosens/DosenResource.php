<?php

namespace App\Filament\Resources\Civitas\Resources\Dosens;

use App\Filament\Resources\Civitas\Dosens\Pages\CreateDosen;
use App\Filament\Resources\Civitas\Dosens\Pages\EditDosen;
use App\Filament\Resources\Civitas\Dosens\Pages\ListDosens;
use App\Filament\Resources\Civitas\Dosens\Schemas\DosenForm;
use App\Filament\Resources\Civitas\Dosens\Tables\DosensTable;
use App\Models\Dosen;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DosenResource extends Resource
{
    // protected static ?string $model = Dosen::class;
    protected static string | UnitEnum | null $navigationGroup = 'Civitas';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DosenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DosensTable::configure($table);
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
            'index' => ListDosens::route('/'),
            'create' => CreateDosen::route('/create'),
            'edit' => EditDosen::route('/{record}/edit'),
        ];
    }
}
