<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin = Role::create(['name' => 'admin']);
        $role_editor = Role::create(['name' => 'editor']);

        $permission_create_rol = Permission::create(['name' => 'create roles']);
        $permission_read_rol = Permission::create(['name' => 'read roles']);
        $permission_update_rol = Permission::create(['name' => 'update roles']);
        $permission_delete_rol = Permission::create(['name' => 'delete roles']);

        $permission_create_diagram = Permission::create(['name' => 'create diagrams']);
        $permission_read_diagram = Permission::create(['name' => 'read diagrams']);
        $permission_update_diagram = Permission::create(['name' => 'update diagrams']);
        $permission_delete_diagram = Permission::create(['name' => 'delete diagrams']);

        $permission_create_place = Permission::create(['name' => 'create place']);
        $permission_read_place = Permission::create(['name' => 'read place']);
        $permission_update_place = Permission::create(['name' => 'update place']);
        $permission_delete_place = Permission::create(['name' => 'delete place']);

        $permission_create_factor = Permission::create(['name' => 'create factor']);
        $permission_read_factor = Permission::create(['name' => 'read factor']);
        $permission_update_factor = Permission::create(['name' => 'update factor']);
        $permission_delete_factor = Permission::create(['name' => 'delete factor']);

        $permission_create_staff = Permission::create(['name' => 'create staff']);
        $permission_read_staff = Permission::create(['name' => 'read staff']);
        $permission_update_staff = Permission::create(['name' => 'update staff']);
        $permission_delete_staff = Permission::create(['name' => 'delete staff']);


        $permission_create_node = Permission::create(['name' => 'create node']);
        $permission_read_node = Permission::create(['name' => 'read node']);
        $permission_update_node = Permission::create(['name' => 'update node']);
        $permission_delete_node = Permission::create(['name' => 'delete node']);


        $permission_create_type = Permission::create(['name' => 'create type']);
        $permission_read_type = Permission::create(['name' => 'read type']);
        $permission_update_type = Permission::create(['name' => 'update type']);
        $permission_delete_type = Permission::create(['name' => 'delete type']);


        $permission_create_error = Permission::create(['name' => 'create error']);
        $permission_read_error = Permission::create(['name' => 'read error']);
        $permission_update_error = Permission::create(['name' => 'update error']);
        $permission_delete_error = Permission::create(['name' => 'delete error']);


        $permissions_admin = [
            //
            $permission_create_rol,
            $permission_read_rol,
            $permission_update_rol,
            $permission_delete_rol,
            //
            $permission_create_diagram,
            $permission_read_diagram,
            $permission_update_diagram,
            $permission_delete_diagram,
            //
            $permission_create_error,
            $permission_read_error,
            $permission_update_error,
            $permission_delete_error,
            //
            $permission_create_factor,
            $permission_read_factor,
            $permission_update_factor,
            $permission_delete_factor,
            //
            $permission_create_place,
            $permission_read_place,
            $permission_update_place,
            $permission_delete_place,
            //
            $permission_create_type,
            $permission_read_type,
            $permission_update_type,
            $permission_delete_type,
            //
            $permission_create_staff,
            $permission_read_staff,
            $permission_update_staff,
            $permission_delete_staff,
            //
            $permission_create_node,
            $permission_read_node,
            $permission_update_node,
            $permission_delete_node
            
        ];

        $permissions_editor = [
            $permission_create_error,
            $permission_read_error,
            $permission_update_error,
            $permission_read_factor,
            $permission_read_place,
            $permission_read_type,
            $permission_read_staff,
            $permission_read_node,
        ];

        $role_admin->syncPermissions($permissions_admin);
        $role_editor->syncPermissions($permissions_editor);

    }
}
