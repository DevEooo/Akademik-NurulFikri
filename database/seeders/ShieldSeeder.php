<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        // Define permissions for each role
        $dosenPermissions = [
            'ViewAny:Dosen', 'View:Dosen', 'Create:Dosen', 'Update:Dosen', 'Delete:Dosen', 'Restore:Dosen', 'ForceDelete:Dosen', 'ForceDeleteAny:Dosen', 'RestoreAny:Dosen', 'Replicate:Dosen', 'Reorder:Dosen',
            'ViewAny:Mahasiswa', 'View:Mahasiswa', 'Create:Mahasiswa', 'Update:Mahasiswa', 'Delete:Mahasiswa', 'Restore:Mahasiswa', 'ForceDelete:Mahasiswa', 'ForceDeleteAny:Mahasiswa', 'RestoreAny:Mahasiswa', 'Replicate:Mahasiswa', 'Reorder:Mahasiswa',
            'ViewAny:JadwalKuliah', 'View:JadwalKuliah', 'Create:JadwalKuliah', 'Update:JadwalKuliah', 'Delete:JadwalKuliah', 'Restore:JadwalKuliah', 'ForceDelete:JadwalKuliah', 'ForceDeleteAny:JadwalKuliah', 'RestoreAny:JadwalKuliah', 'Replicate:JadwalKuliah', 'Reorder:JadwalKuliah',
            'ViewAny:MataKuliah', 'View:MataKuliah', 'Create:MataKuliah', 'Update:MataKuliah', 'Delete:MataKuliah', 'Restore:MataKuliah', 'ForceDelete:MataKuliah', 'ForceDeleteAny:MataKuliah', 'RestoreAny:MataKuliah', 'Replicate:MataKuliah', 'Reorder:MataKuliah',
            'ViewAny:Penilaian', 'View:Penilaian', 'Create:Penilaian', 'Update:Penilaian', 'Delete:Penilaian', 'Restore:Penilaian', 'ForceDelete:Penilaian', 'ForceDeleteAny:Penilaian', 'RestoreAny:Penilaian', 'Replicate:Penilaian', 'Reorder:Penilaian',
            'ViewAny:KRS', 'View:KRS', 'Create:KRS', 'Update:KRS', 'Delete:KRS', 'Restore:KRS', 'ForceDelete:KRS', 'ForceDeleteAny:KRS', 'RestoreAny:KRS', 'Replicate:KRS', 'Reorder:KRS',
        ];

        $staffPermissions = [
            'ViewAny:Staff', 'View:Staff', 'Create:Staff', 'Update:Staff', 'Delete:Staff', 'Restore:Staff', 'ForceDelete:Staff', 'ForceDeleteAny:Staff', 'RestoreAny:Staff', 'Replicate:Staff', 'Reorder:Staff',
            'ViewAny:Pengumuman', 'View:Pengumuman', 'Create:Pengumuman', 'Update:Pengumuman', 'Delete:Pengumuman', 'Restore:Pengumuman', 'ForceDelete:Pengumuman', 'ForceDeleteAny:Pengumuman', 'RestoreAny:Pengumuman', 'Replicate:Pengumuman', 'Reorder:Pengumuman',
            'ViewAny:Ruangan', 'View:Ruangan', 'Create:Ruangan', 'Update:Ruangan', 'Delete:Ruangan', 'Restore:Ruangan', 'ForceDelete:Ruangan', 'ForceDeleteAny:Ruangan', 'RestoreAny:Ruangan', 'Replicate:Ruangan', 'Reorder:Ruangan',
            'ViewAny:TahunAjaran', 'View:TahunAjaran', 'Create:TahunAjaran', 'Update:TahunAjaran', 'Delete:TahunAjaran', 'Restore:TahunAjaran', 'ForceDelete:TahunAjaran', 'ForceDeleteAny:TahunAjaran', 'RestoreAny:TahunAjaran', 'Replicate:TahunAjaran', 'Reorder:TahunAjaran',
            'ViewAny:ProgramStudi', 'View:ProgramStudi', 'Create:ProgramStudi', 'Update:ProgramStudi', 'Delete:ProgramStudi', 'Restore:ProgramStudi', 'ForceDelete:ProgramStudi', 'ForceDeleteAny:ProgramStudi', 'RestoreAny:ProgramStudi', 'Replicate:ProgramStudi', 'Reorder:ProgramStudi',
        ];

        $adminPermissions = Permission::all()->pluck('name')->toArray(); // All permissions for admin

        // Assign to roles
        $dosenRole = Role::findByName('dosen');
        if ($dosenRole) {
            $dosenRole->syncPermissions($dosenPermissions);
        }

        $staffRole = Role::findByName('staff');
        if ($staffRole) {
            $staffRole->syncPermissions($staffPermissions);
        }

        $adminRole = Role::findByName('super_admin');
        if ($adminRole) {
            $adminRole->syncPermissions($adminPermissions);
        }
    }
}
