<?php

namespace App\Filament\Resources\MasterData\TahunAjarans;

use App\Filament\Resources\MasterData\TahunAjarans\Pages\CreateTahunAjaran;
use App\Filament\Resources\MasterData\TahunAjarans\Pages\EditTahunAjaran;
use App\Filament\Resources\MasterData\TahunAjarans\Pages\ListTahunAjaran;
use App\Filament\Resources\MasterData\TahunAjarans\Schemas\TahunAjaranForm;
use App\Filament\Resources\MasterData\TahunAjarans\Tables\TahunAjaranTable;
use App\Models\TahunAjaran;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $label = "Tahun Ajaran";
    protected static ?string $slug = "tahun-ajaran";
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCalendar;

    public static function form(Schema $schema): Schema
    {
        return TahunAjaranForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return TahunAjaranTable::table($table);
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
            'index' => ListTahunAjaran::route('/'),
            'create' => CreateTahunAjaran::route('/create'),
            'edit' => EditTahunAjaran::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:TahunAjaran') ?? false;
    }
}
