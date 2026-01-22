<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Dosen;
use Illuminate\Auth\Access\HandlesAuthorization;

class DosenPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Dosen');
    }

    public function view(AuthUser $authUser, Dosen $dosen): bool
    {
        return $authUser->can('View:Dosen');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Dosen');
    }

    public function update(AuthUser $authUser, Dosen $dosen): bool
    {
        return $authUser->can('Update:Dosen');
    }

    public function delete(AuthUser $authUser, Dosen $dosen): bool
    {
        return $authUser->can('Delete:Dosen');
    }

    public function restore(AuthUser $authUser, Dosen $dosen): bool
    {
        return $authUser->can('Restore:Dosen');
    }

    public function forceDelete(AuthUser $authUser, Dosen $dosen): bool
    {
        return $authUser->can('ForceDelete:Dosen');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Dosen');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Dosen');
    }

    public function replicate(AuthUser $authUser, Dosen $dosen): bool
    {
        return $authUser->can('Replicate:Dosen');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Dosen');
    }

}