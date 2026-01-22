<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KRS;
use Illuminate\Auth\Access\HandlesAuthorization;

class KRSPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KRS');
    }

    public function view(AuthUser $authUser, KRS $kRS): bool
    {
        return $authUser->can('View:KRS');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KRS');
    }

    public function update(AuthUser $authUser, KRS $kRS): bool
    {
        return $authUser->can('Update:KRS');
    }

    public function delete(AuthUser $authUser, KRS $kRS): bool
    {
        return $authUser->can('Delete:KRS');
    }

    public function restore(AuthUser $authUser, KRS $kRS): bool
    {
        return $authUser->can('Restore:KRS');
    }

    public function forceDelete(AuthUser $authUser, KRS $kRS): bool
    {
        return $authUser->can('ForceDelete:KRS');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:KRS');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:KRS');
    }

    public function replicate(AuthUser $authUser, KRS $kRS): bool
    {
        return $authUser->can('Replicate:KRS');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:KRS');
    }

}