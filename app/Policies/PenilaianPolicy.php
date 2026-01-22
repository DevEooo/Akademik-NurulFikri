<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Penilaian;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenilaianPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Penilaian');
    }

    public function view(AuthUser $authUser, Penilaian $penilaian): bool
    {
        return $authUser->can('View:Penilaian');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Penilaian');
    }

    public function update(AuthUser $authUser, Penilaian $penilaian): bool
    {
        return $authUser->can('Update:Penilaian');
    }

    public function delete(AuthUser $authUser, Penilaian $penilaian): bool
    {
        return $authUser->can('Delete:Penilaian');
    }

    public function restore(AuthUser $authUser, Penilaian $penilaian): bool
    {
        return $authUser->can('Restore:Penilaian');
    }

    public function forceDelete(AuthUser $authUser, Penilaian $penilaian): bool
    {
        return $authUser->can('ForceDelete:Penilaian');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Penilaian');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Penilaian');
    }

    public function replicate(AuthUser $authUser, Penilaian $penilaian): bool
    {
        return $authUser->can('Replicate:Penilaian');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Penilaian');
    }

}