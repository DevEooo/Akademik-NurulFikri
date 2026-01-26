<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class HashPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hash-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix roles and passwords';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('roles')->get();
        $roleModel = Role::class;
        $staffRole = $roleModel::where('name', 'staff ')->first();
        if ($staffRole) {
            $staffRole->name = 'staff';
            $staffRole->save();
            $this->info('Renamed role from "staff " to "staff"');
        }
        $dosenRole = $roleModel::where('name', 'dosen ')->first();
        if ($dosenRole) {
            $dosenRole->name = 'dosen';
            $dosenRole->save();
            $this->info('Renamed role from "dosen " to "dosen"');
        }
        $superAdminRole = $roleModel::where('name', 'super_admin ')->first();
        if ($superAdminRole) {
            $superAdminRole->name = 'super_admin';
            $superAdminRole->save();
            $this->info('Renamed role from "super_admin " to "super_admin"');
        }

        $users = User::with('roles')->get();
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->join(', ');
            $verified = $user->email_verified_at ? 'verified' : 'not verified';
            $this->info($user->email . ': ' . $roles . ' - ' . $verified);
            if ($user->hasRole('staff')) {
                $user->password = Hash::make('password');
                $user->save();
                $this->info('Set password to "password" for ' . $user->email);
            } elseif (strlen($user->getOriginal('password')) < 60) {
                $user->password = Hash::make($user->getOriginal('password'));
                $user->save();
                $this->info('Hashed password for ' . $user->email);
            } else {
                $this->info('Password already hashed for ' . $user->email);
            }
        }
        $this->info('Done.');
    }
}
