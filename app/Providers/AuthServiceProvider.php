<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Dosen;
use App\Models\JadwalKuliah;
use App\Models\KRS;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Pengumuman;
use App\Models\Penilaian;
use App\Models\ProgramStudi;
use App\Models\Ruangan;
use App\Models\Staff;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Policies\DosenPolicy;
use App\Policies\JadwalKuliahPolicy;
use App\Policies\KRSPolicy;
use App\Policies\MahasiswaPolicy;
use App\Policies\MataKuliahPolicy;
use App\Policies\PengumumanPolicy;
use App\Policies\PenilaianPolicy;
use App\Policies\ProgramStudiPolicy;
use App\Policies\RuanganPolicy;
use App\Policies\StaffPolicy;
use App\Policies\TahunAjaranPolicy;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Dosen::class => DosenPolicy::class,
        JadwalKuliah::class => JadwalKuliahPolicy::class,
        KRS::class => KRSPolicy::class,
        Mahasiswa::class => MahasiswaPolicy::class,
        MataKuliah::class => MataKuliahPolicy::class,
        Pengumuman::class => PengumumanPolicy::class,
        Penilaian::class => PenilaianPolicy::class,
        ProgramStudi::class => ProgramStudiPolicy::class,
        Ruangan::class => RuanganPolicy::class,
        Staff::class => StaffPolicy::class,
        TahunAjaran::class => TahunAjaranPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
