<?php

namespace App\Filament\Resources\Civitas\Users\Pages;

use App\Filament\Resources\Civitas\Users\UserResource;
use App\Filament\Resources\Civitas\Users\Schemas\UserForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function form(Schema $schema): Schema
    {
        return UserForm::configure($schema, true);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $user = $this->record;
        $roleIds = $this->data['role'] ?? [];

        // Convert role IDs to role names
        if (!empty($roleIds)) {
            $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
