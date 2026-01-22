<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MataKuliah;
use Illuminate\Auth\Access\HandlesAuthorization;

class MataKuliahPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MataKuliah');
    }

    public function view(AuthUser $authUser, MataKuliah $mataKuliah): bool
    {
        return $authUser->can('View:MataKuliah');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MataKuliah');
    }

    public function update(AuthUser $authUser, MataKuliah $mataKuliah): bool
    {
        return $authUser->can('Update:MataKuliah');
    }

    public function delete(AuthUser $authUser, MataKuliah $mataKuliah): bool
    {
        return $authUser->can('Delete:MataKuliah');
    }

    public function restore(AuthUser $authUser, MataKuliah $mataKuliah): bool
    {
        return $authUser->can('Restore:MataKuliah');
    }

    public function forceDelete(AuthUser $authUser, MataKuliah $mataKuliah): bool
    {
        return $authUser->can('ForceDelete:MataKuliah');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MataKuliah');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MataKuliah');
    }

    public function replicate(AuthUser $authUser, MataKuliah $mataKuliah): bool
    {
        return $authUser->can('Replicate:MataKuliah');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MataKuliah');
    }

}