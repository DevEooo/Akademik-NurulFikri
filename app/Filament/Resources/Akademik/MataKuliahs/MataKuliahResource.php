<?php

namespace App\Filament\Resources\Akademik\MataKuliahs;

use App\Filament\Resources\Akademik\MataKuliahs\Pages\CreateMataKuliah;
use App\Filament\Resources\Akademik\MataKuliahs\Pages\EditMataKuliah;
use App\Filament\Resources\Akademik\MataKuliahs\Pages\ListMataKuliahs;
use App\Filament\Resources\Akademik\MataKuliahs\Schemas\MataKuliahForm;
use App\Filament\Resources\Akademik\MataKuliahs\Tables\MataKuliahTable;
use App\Models\MataKuliah;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;
    protected static UnitEnum|string|null $navigationGroup = 'Akademik';
    protected static ?string $label = "Mata Kuliah";
    protected static ?string $slug = "mata-kuliah";
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function form(Schema $schema): Schema
    {
        return MataKuliahForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return MataKuliahTable::table($table);
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
            'index' => ListMataKuliahs::route('/'),
            'create' => CreateMataKuliah::route('/create'),
            'edit' => EditMataKuliah::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:MataKuliah') ?? false;
    }
}
