<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Rolleri oluştur (varsa silmeden ekle)
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $personnelRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'field_personnel', 'guard_name' => 'web']);

        // 2. Kullanıcıları oluştur
        $admin = User::firstOrCreate(['email' => 'admin@sahadenetim.com'], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);

        $manager = User::firstOrCreate(['email' => 'manager@sahadenetim.com'], [
            'name' => 'Regional Manager',
            'password' => bcrypt('password'),
        ]);
        $manager->assignRole($managerRole);

        $personnel = User::firstOrCreate(['email' => 'personel@sahadenetim.com'], [
            'name' => 'Field Personnel',
            'password' => bcrypt('password'),
        ]);
        $personnel->assignRole($personnelRole);

        // Sisteme sanki admin giriş yapmış gibi davran ki loglara "Admin" düşsün.
        auth()->login($admin);

        // 3. Denetim Noktaları Oluştur
        $point1 = \App\Models\AuditPoint::create([
            'name' => 'Central Warehouse Audit',
            'description' => 'Monthly routine central warehouse checkpoint',
            'category' => 'warehouse',
            'latitude' => 41.0082,
            'longitude' => 28.9784,
            'address' => 'Istanbul Central',
            'is_active' => true,
        ]);

        $point2 = \App\Models\AuditPoint::create([
            'name' => 'Branch 1 Electrical Panel',
            'description' => 'Branch 1 main switch and electrical check',
            'category' => 'electrical',
            'latitude' => 41.0200,
            'longitude' => 29.0000,
            'address' => 'Istanbul Branch 1',
            'is_active' => true,
        ]);

        // 4. Görevler Oluştur
        \App\Models\Task::create([
            'title' => 'Warehouse Inventory',
            'description' => 'Counting Section A stock and entering into the system.',
            'status' => 'pending',
            'priority' => 'high',
            'audit_point_id' => $point1->id,
            'assigned_to' => $personnel->id,
            'assigned_manager' => $manager->id,
            'due_date' => now()->addDays(2),
        ]);

        \App\Models\Task::create([
            'title' => 'Panel Maintenance',
            'description' => 'Thermal camera measurement of electrical panel.',
            'status' => 'completed',
            'priority' => 'normal',
            'audit_point_id' => $point2->id,
            'assigned_to' => $personnel->id,
            'assigned_manager' => $manager->id,
            'due_date' => now()->subDays(1),
        ]);

        auth()->logout();
    }
}
