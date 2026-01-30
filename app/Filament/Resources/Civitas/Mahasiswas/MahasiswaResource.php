<?php

namespace App\Filament\Resources\Civitas\Mahasiswas;

use App\Filament\Resources\Civitas\Mahasiswas\Pages\CreateMahasiswa;
use App\Filament\Resources\Civitas\Mahasiswas\Pages\EditMahasiswa;
use App\Filament\Resources\Civitas\Mahasiswas\Pages\ListMahasiswas;
use App\Filament\Resources\Civitas\Mahasiswas\Schemas\MahasiswaForm;
use App\Filament\Resources\Civitas\Mahasiswas\Tables\MahasiswaTable;
use App\Models\Mahasiswa;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;
    protected static UnitEnum|string|null $navigationGroup = 'Civitas';
    protected static ?string $navigationLabel = 'Daftar Mahasiswa';
    protected static ?string $label = "Daftar Mahasiswa";
    protected static ?string $slug = 'daftar-mahasiswa';
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return MahasiswaForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return MahasiswaTable::table($table);
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
