<?php

namespace App\Filament\Resources\Civitas\Staff;

use App\Filament\Resources\Civitas\Staff\Pages\CreateStaff;
use App\Filament\Resources\Civitas\Staff\Pages\EditStaff;
use App\Filament\Resources\Civitas\Staff\Pages\ListStaff;
use App\Filament\Resources\Civitas\Staff\Schemas\StaffForm;
use App\Filament\Resources\Civitas\Staff\Tables\StaffTable;
use App\Models\Staff;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;
    protected static UnitEnum|string|null $navigationGroup = 'Civitas';
    protected static ?string $label = "Staff";
    protected static ?string $slug = "staff";
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedBriefcase;

    public static function form(Schema $schema): Schema
    {
        return StaffForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffTable::table($table);
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
            'index' => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:Staff') ?? false;
    }
}
