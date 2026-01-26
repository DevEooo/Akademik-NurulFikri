<?php

namespace App\Filament\Resources\Civitas\Users\Pages;

use App\Filament\Resources\Civitas\Users\UserResource;
use App\Filament\Resources\Civitas\Users\Schemas\UserForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function form(Schema $schema): Schema
    {
        return UserForm::configure($schema, false);
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        $roleIds = $this->data['role'] ?? [];

        // Convert role IDs to role names
        if (!empty($roleIds)) {
            $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        }

        // Verify email
        $user->email_verified_at = now();
        $user->save();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
