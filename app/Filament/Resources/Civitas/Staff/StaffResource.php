<?php

namespace App\Filament\Resources\Civitas\Staff;

use App\Filament\Resources\Civitas\Staff\Pages\CreateStaff;
use App\Filament\Resources\Civitas\Staff\Pages\EditStaff;
use App\Filament\Resources\Civitas\Staff\Pages\ListStaff;
use App\Filament\Resources\Civitas\Staff\Schemas\StaffForm;
use App\Filament\Resources\Civitas\Staff\Tables\StaffTable;
use App\Models\Staff;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;use Illuminate\Support\Facades\Auth;
class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;
    protected static string | UnitEnum | null $navigationGroup = 'Civitas';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StaffForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffTable::configure($table);
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
    }}
