<?php

namespace App\Filament\Resources\MasterData\Ruangans;

use App\Filament\Resources\MasterData\Ruangans\Pages\CreateRuangan;
use App\Filament\Resources\MasterData\Ruangans\Pages\EditRuangan;
use App\Filament\Resources\MasterData\Ruangans\Pages\ListRuangan;
use App\Filament\Resources\MasterData\Ruangans\Schemas\RuanganForm;
use App\Filament\Resources\MasterData\Ruangans\Tables\RuanganTable;
use App\Models\Ruangan;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RuanganResource extends Resource
{
    protected static ?string $model = Ruangan::class;
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $label = "Ruangan";
    protected static ?string $slug = "ruangan";
    protected static BackedEnum|string|null $navigationIcon = Heroicon::HomeModern;

    public static function form(Schema $schema): Schema
    {
        return RuanganForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return RuanganTable::table($table);
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
            'index' => ListRuangan::route('/'),
            'create' => CreateRuangan::route('/create'),
            'edit' => EditRuangan::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:Ruangan') ?? false;
    }
}
