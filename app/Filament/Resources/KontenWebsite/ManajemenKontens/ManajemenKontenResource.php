<?php

namespace App\Filament\Resources\ManajemenKontens;

use App\Filament\Resources\ManajemenKontens\Pages\CreateManajemenKonten;
use App\Filament\Resources\ManajemenKontens\Pages\EditManajemenKonten;
use App\Filament\Resources\ManajemenKontens\Pages\ListManajemenKontens;
use App\Filament\Resources\ManajemenKontens\Schemas\ManajemenKontenForm;
use App\Filament\Resources\ManajemenKontens\Tables\ManajemenKontensTable;
use App\Models\ManajemenKonten;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManajemenKontenResource extends Resource
{
    protected static ?string $model = ManajemenKonten::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return ManajemenKontenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManajemenKontensTable::configure($table);
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
            'index' => ListManajemenKontens::route('/'),
            'create' => CreateManajemenKonten::route('/create'),
            'edit' => EditManajemenKonten::route('/{record}/edit'),
        ];
    }
}
