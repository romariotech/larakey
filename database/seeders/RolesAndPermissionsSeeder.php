<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa o cache do Spatie
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1. Criar as Permissões
        $permissions = [
            'view_dashboard',
            'manage_users',
            'create_properties',
            'edit_properties',
            'delete_properties',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Criar os Perfis (Roles) e Vincular Permissões

        // Perfil MANAGER (Gerente)
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            'view_dashboard',
            'create_properties',
            'edit_properties',
            'view_reports',
        ]);

        // Perfil ADMIN (Administrador)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Perfil USER (Utilizador Comum)
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'view_dashboard',
        ]);
    }
}
