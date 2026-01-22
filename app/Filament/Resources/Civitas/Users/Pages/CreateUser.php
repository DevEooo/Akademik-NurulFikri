<?php

namespace App\Filament\Resources\Civitas\Users\Pages;

use App\Filament\Resources\Civitas\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
