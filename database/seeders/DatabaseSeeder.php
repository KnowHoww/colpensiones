<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'administrador']);
        $roleAnalista = Role::create(['name' => 'analista']);

        \App\Models\States::factory()->create([
            'name' => 'Activo',
        ]);
        \App\Models\States::factory()->create([
            'name' => 'Inactivo',
        ]);
        \App\Models\States::factory()->create([
            'name' => 'Solicitado',
        ]);
        \App\Models\States::factory()->create([
            'name' => 'Asignado',
        ]);
        \App\Models\States::factory()->create([
            'name' => 'En revisión',
        ]);
        \App\Models\States::factory()->create([
            'name' => 'Finalizado',
        ]);
        \App\Models\States::factory()->create([
            'name' => 'Devuelto',
        ]);
        \App\Models\States::factory()->create([
            'name' => 'Cancelado',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Sebastián',
            'lastname' => 'Chaparro',
            'numberDocument' => '1022399551',
            'phone' => '3168642973',
            'idTypeDocument' => 1,
            'idCity' => 6881,
            'estado' => 1,
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ])->assignRole('admin');

        \App\Models\User::factory()->create([
            'name' => 'Mauricio',
            'lastname' => 'Chaparro',
            'numberDocument' => '79270751',
            'phone' => '313158133',
            'idTypeDocument' => 1,
            'idCity' => 6881,
            'estado' => 1,
            'email' => 'mauricio@chaparro.com',
            'password' => bcrypt('12345'),
        ]);
        
        $permission = Permission::create(['name' => 'user.index'])->syncRoles([$roleAdmin,$roleAnalista]);
        $permission = Permission::create(['name' => 'user.edit'])->assignRole($roleAdmin);
        $permission = Permission::create(['name' => 'user.create'])->assignRole($roleAdmin);

    }
}
