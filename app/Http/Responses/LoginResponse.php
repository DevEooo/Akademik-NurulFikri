<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = $request->user();

        if ($user->hasRole('super_admin')) {
            return redirect('/admin');
        } elseif ($user->hasRole('dosen')) {
            return redirect('/dosen');
        } elseif ($user->hasRole('staff')) {
            return redirect('/staff');
        } else {
            // Default to admin or handle as needed
            return redirect('/admin');
        }
    }
}
