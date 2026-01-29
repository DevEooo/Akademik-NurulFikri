<?php

namespace App\Filament\Resources\KontenWebsite\ManajemenKontens;

use App\Filament\Resources\KontenWebsite\ManajemenKontens\Pages\CreateManajemenKontens;
use App\Filament\Resources\KontenWebsite\ManajemenKontens\Pages\EditManajemenKonten;
use App\Filament\Resources\KontenWebsite\ManajemenKontens\Pages\ListManajemenKontens;
use App\Filament\Resources\KontenWebsite\ManajemenKontens\Schemas\ManajemenKontenForm;
use App\Filament\Resources\KontenWebsite\ManajemenKontens\Tables\ManajemenKontensTable;
use App\Models\ManajemenKonten;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManajemenKontenResource extends Resource
{
    protected static ?string $model = ManajemenKonten::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Newspaper;

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
            'create' => CreateManajemenKontens::route('/create'),
            'edit' => EditManajemenKonten::route('/{record}/edit'),
        ];
    }
}
