<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return redirect('/portal');
        } 

        if ($user->hasRole('dosen')) {
            return redirect('/dosen');
        } 
        
        if ($user->hasRole('staff')) {
            return redirect('/staff');
        } 

        return redirect()->to('/welcome');
    }
}
