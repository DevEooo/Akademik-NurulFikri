<?php

namespace App\Filament\Resources\MasterData\ProgramStudis;

use App\Filament\Resources\MasterData\ProgramStudis\Pages\CreateProgramStudi;
use App\Filament\Resources\MasterData\ProgramStudis\Pages\EditProgramStudi;
use App\Filament\Resources\MasterData\ProgramStudis\Pages\ListProgramStudis;
use App\Filament\Resources\MasterData\ProgramStudis\Schemas\ProgramStudiForm;
use App\Filament\Resources\MasterData\ProgramStudis\Tables\ProgramStudisTable;
use App\Models\ProgramStudi;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgramStudiResource extends Resource
{
    protected static ?string $model = ProgramStudi::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';
    public static function form(Schema $schema): Schema
    {
        return ProgramStudiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramStudisTable::configure($table);
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
            'index' => ListProgramStudis::route('/'),
            'create' => CreateProgramStudi::route('/create'),
            'edit' => EditProgramStudi::route('/{record}/edit'),
        ];
    }
}
