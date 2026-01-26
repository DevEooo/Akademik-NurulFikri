<?php

namespace App\Filament\Resources\MasterData\Ruangans;

use App\Filament\Resources\MasterData\MasterDataCluster;
use App\Filament\Resources\MasterData\Ruangans\Pages\CreateRuangan;
use App\Filament\Resources\MasterData\Ruangans\Pages\EditRuangan;
use App\Filament\Resources\MasterData\Ruangans\Pages\ListRuangans;
use App\Filament\Resources\MasterData\Ruangans\Schemas\RuanganForm;
use App\Filament\Resources\MasterData\Ruangans\Tables\RuangansTable;
use App\Models\Ruangan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RuanganResource extends Resource
{
    protected static ?string $model = Ruangan::class;
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::HomeModern;

    public static function form(Schema $schema): Schema
    {
        return RuanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RuangansTable::configure($table);
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
            'index' => ListRuangans::route('/'),
            'create' => CreateRuangan::route('/create'),
            'edit' => EditRuangan::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:Ruangan') ?? false;
    }
}
