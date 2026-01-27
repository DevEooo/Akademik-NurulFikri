<?php

namespace App\Filament\Resources\Civitas\Dosens;

use App\Filament\Resources\Civitas\Dosens\Pages\CreateDosen;
use App\Filament\Resources\Civitas\Dosens\Pages\EditDosen;
use App\Filament\Resources\Civitas\Dosens\Pages\ListDosens;
use App\Filament\Resources\Civitas\Dosens\Schemas\DosenForm;
use App\Filament\Resources\Civitas\Dosens\Tables\DosenTable;
use App\Models\Dosen;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;
    protected static UnitEnum|string|null $navigationGroup = 'Civitas';
    protected static ?string $label = "Dosen";
    protected static ?string $slug = "dosen";
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return DosenForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return DosenTable::table($table);
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

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:Dosen') ?? false;
    }
}
