<?php

namespace App\Filament\Resources\MasterData\ProgramStudis;

use App\Filament\Resources\MasterData\ProgramStudis\Pages\CreateProgramStudi;
use App\Filament\Resources\MasterData\ProgramStudis\Pages\EditProgramStudi;
use App\Filament\Resources\MasterData\ProgramStudis\Pages\ListProgramStudi;
use App\Filament\Resources\MasterData\ProgramStudis\Schemas\ProgramStudiForm;
use App\Filament\Resources\MasterData\ProgramStudis\Tables\ProgramStudiTable;
use App\Models\ProgramStudi;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProgramStudiResource extends Resource
{
    protected static ?string $model = ProgramStudi::class;
    protected static UnitEnum|string|null $navigationGroup = 'Master Data';
    protected static ?string $label = "Program Studi";
    protected static ?string $slug = "program-studi";
    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return ProgramStudiForm::form($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramStudiTable::table($table);
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
            'index' => ListProgramStudi::route('/'),
            'create' => CreateProgramStudi::route('/create'),
            'edit' => EditProgramStudi::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->can('ViewAny:ProgramStudi') ?? false;
    }
}
