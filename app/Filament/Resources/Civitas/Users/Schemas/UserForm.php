<?php

namespace App\Filament\Resources\Civitas\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->revealable(),
                Select::make('role')
                    ->options([
                        'Super Admin' => 'Super Admin',
                        'User' => 'User',
                        'Staff' => 'Staff',
                    ])
                    ->required(),
            ]);
    }
}
