<?php

namespace App\Filament\Resources\Civitas\Mahasiswas;

use App\Filament\Resources\Civitas\Mahasiswas\Pages\CreateMahasiswa;
use App\Filament\Resources\Civitas\Mahasiswas\Pages\EditMahasiswa;
use App\Filament\Resources\Civitas\Mahasiswas\Pages\ListMahasiswas;
use App\Filament\Resources\Civitas\Mahasiswas\Schemas\MahasiswaForm;
use App\Filament\Resources\Civitas\Mahasiswas\Tables\MahasiswasTable;
use App\Models\Mahasiswa;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;
    protected static string | UnitEnum | null $navigationGroup = 'Civitas';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MahasiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MahasiswasTable::configure($table);
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
            'index' => ListMahasiswas::route('/'),
            'create' => CreateMahasiswa::route('/create'),
            'edit' => EditMahasiswa::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:Mahasiswa') ?? false;
    }
}
