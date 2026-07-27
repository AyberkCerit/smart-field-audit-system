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
            'name' => 'Admin Kullanıcısı',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);

        $manager = User::firstOrCreate(['email' => 'manager@sahadenetim.com'], [
            'name' => 'Bölge Yöneticisi',
            'password' => bcrypt('password'),
        ]);
        $manager->assignRole($managerRole);

        $personnel = User::firstOrCreate(['email' => 'personel@sahadenetim.com'], [
            'name' => 'Saha Personeli',
            'password' => bcrypt('password'),
        ]);
        $personnel->assignRole($personnelRole);

        // Sisteme sanki admin giriş yapmış gibi davran ki loglara "Admin" düşsün.
        auth()->login($admin);

        // 3. Denetim Noktaları Oluştur
        $point1 = \App\Models\AuditPoint::create([
            'name' => 'Merkez Depo Denetimi',
            'description' => 'Aylık rutin merkez depo kontrol noktası',
            'category' => 'warehouse',
            'latitude' => 41.0082,
            'longitude' => 28.9784,
            'address' => 'İstanbul Merkez',
            'is_active' => true,
        ]);

        $point2 = \App\Models\AuditPoint::create([
            'name' => 'Şube 1 Elektrik Panosu',
            'description' => 'Şube 1 ana şalter ve elektrik kontrolü',
            'category' => 'electrical',
            'latitude' => 41.0200,
            'longitude' => 29.0000,
            'address' => 'İstanbul Şube 1',
            'is_active' => true,
        ]);

        // 4. Görevler Oluştur
        \App\Models\Task::create([
            'title' => 'Depo Sayımı',
            'description' => 'Bölüm A stoklarının sayılarak sisteme girilmesi.',
            'status' => 'pending',
            'priority' => 'high',
            'audit_point_id' => $point1->id,
            'assigned_to' => $personnel->id,
            'assigned_manager' => $manager->id,
            'due_date' => now()->addDays(2),
        ]);

        \App\Models\Task::create([
            'title' => 'Pano Bakımı',
            'description' => 'Elektrik panosunun termal kamera ile ölçümü.',
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
