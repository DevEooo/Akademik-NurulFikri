<?php

namespace App\Filament\Resources\MasterData\TahunAjarans;

use App\Filament\Resources\MasterData\TahunAjarans\Pages\CreateTahunAjaran;
use App\Filament\Resources\MasterData\TahunAjarans\Pages\EditTahunAjaran;
use App\Filament\Resources\MasterData\TahunAjarans\Pages\ListTahunAjarans;
use App\Filament\Resources\MasterData\TahunAjarans\Schemas\TahunAjaranForm;
use App\Filament\Resources\MasterData\TahunAjarans\Tables\TahunAjaransTable;
use App\Models\TahunAjaran;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    public static function form(Schema $schema): Schema
    {
        return TahunAjaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TahunAjaransTable::configure($table);
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
            'index' => ListTahunAjarans::route('/'),
            'create' => CreateTahunAjaran::route('/create'),
            'edit' => EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}
