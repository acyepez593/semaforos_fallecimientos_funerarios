<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class RolePermissionSeeder.
 *
 * @see https://spatie.be/docs/laravel-permission/v5/basic-usage/multiple-guards
 *
 * @package App\Database\Seeds
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Enable these options if you need same role and other permission for User Model
         * Else, please follow the below steps for admin guard
         */

        // Create Roles and Permissions
        // $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        // $roleAdmin = Role::create(['name' => 'admin']);
        // $roleEditor = Role::create(['name' => 'editor']);
        // $roleUser = Role::create(['name' => 'user']);


        // Permission List as array
        $permissions = [

            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard.view',
                    'dashboard.edit',
                ]
            ],
            [
                'group_name' => 'admin',
                'permissions' => [
                    // admin Permissions
                    'admin.create',
                    'admin.view',
                    'admin.edit',
                    'admin.delete',
                    
                ]
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    // role Permissions
                    'role.create',
                    'role.view',
                    'role.edit',
                    'role.delete',
                    
                ]
            ],
            [
                'group_name' => 'profile',
                'permissions' => [
                    // profile Permissions
                    'profile.view',
                    'profile.edit',
                    'profile.delete',
                    
                ]
            ],
            [
                'group_name' => 'catalogo',
                'permissions' => [
                    // catalogo Permissions
                    'catalogo.create',
                    'catalogo.view',
                    'catalogo.edit',
                    'catalogo.delete',
                    
                ]
            ],
            [
                'group_name' => 'proteccion',
                'permissions' => [
                    // proteccion Permissions
                    'proteccion.create',
                    'proteccion.view',
                    'proteccion.edit',
                    'proteccion.delete',
                    
                ]
            ],
            [
                'group_name' => 'estado',
                'permissions' => [
                    // estado Permissions
                    'estado.create',
                    'estado.view',
                    'estado.edit',
                    'estado.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoRespuesta',
                'permissions' => [
                    // tipoRespuesta Permissions
                    'tipoRespuesta.create',
                    'tipoRespuesta.view',
                    'tipoRespuesta.edit',
                    'tipoRespuesta.delete',
                    
                ]
            ],
            [
                'group_name' => 'tipoIngreso',
                'permissions' => [
                    // tipoIngreso Permissions
                    'tipoIngreso.create',
                    'tipoIngreso.view',
                    'tipoIngreso.edit',
                    'tipoIngreso.delete',
                    
                ]
            ],
            [
                'group_name' => 'semaforo',
                'permissions' => [
                    // semaforo Permissions
                    'semaforo.create',
                    'semaforo.view',
                    'semaforo.edit',
                    'semaforo.delete',
                    
                ]
            ],
            [
                'group_name' => 'expediente',
                'permissions' => [
                    // expediente Permissions
                    'expediente.create',
                    'expediente.view',
                    'expediente.edit',
                    'expediente.delete',
                    
                ]
            ],
            [
                'group_name' => 'reporte',
                'permissions' => [
                    // reporte Permissions
                    'reporte.view',
                    'reporte.download',
                ]
            ],
        ];


        // Create and Assign Permissions
        // for ($i = 0; $i < count($permissions); $i++) {
        //     $permissionGroup = $permissions[$i]['group_name'];
        //     for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
        //         // Create Permission
        //         $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup]);
        //         $roleSuperAdmin->givePermissionTo($permission);
        //         $permission->assignRole($roleSuperAdmin);
        //     }
        // }

        // Do same for the admin guard for tutorial purposes.
        $admin = Admin::where('username', 'superadmin')->first();
        $roleSuperAdmin = $this->maybeCreateSuperAdminRole($admin);

        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                $permissionExist = Permission::where('name', $permissions[$i]['permissions'][$j])->first();
                if (is_null($permissionExist)) {
                    $permission = Permission::create(
                        [
                            'name' => $permissions[$i]['permissions'][$j],
                            'group_name' => $permissionGroup,
                            'guard_name' => 'admin'
                        ]
                    );
                    $roleSuperAdmin->givePermissionTo($permission);
                    $permission->assignRole($roleSuperAdmin);
                }
            }
        }

        // Assign super admin role permission to superadmin user
        if ($admin) {
            $admin->assignRole($roleSuperAdmin);
        }
    }

    private function maybeCreateSuperAdminRole($admin): Role
    {
        if (is_null($admin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        } else {
            $roleSuperAdmin = Role::where('name', 'superadmin')->where('guard_name', 'admin')->first();
        }

        if (is_null($roleSuperAdmin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        }

        return $roleSuperAdmin;
    }
}
