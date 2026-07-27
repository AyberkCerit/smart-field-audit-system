<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\AuditPoint;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Rolleri oluştur (eğer yoksa)
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $fieldPersonnelRole = Role::firstOrCreate(['name' => 'field_personnel', 'guard_name' => 'web']);

        // Admin Kullanıcısı
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Yönetici Kullanıcısı
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Yönetici User',
                'password' => Hash::make('password'),
            ]
        );
        if (!$manager->hasRole('manager')) {
            $manager->assignRole($managerRole);
        }

        // Saha Personeli Kullanıcısı
        $fieldPersonnel = User::firstOrCreate(
            ['email' => 'personnel@example.com'],
            [
                'name' => 'Saha Personeli',
                'password' => Hash::make('password'),
            ]
        );
        if (!$fieldPersonnel->hasRole('field_personnel')) {
            $fieldPersonnel->assignRole($fieldPersonnelRole);
        }

        // Sahte Audit Points (Denetim Noktaları) oluştur (eğer yoksa)
        if (AuditPoint::count() == 0) {
            for ($i = 1; $i <= 5; $i++) {
                AuditPoint::create([
                    'name' => 'Denetim Noktası ' . $i,
                    'description' => 'Test denetim noktası açıklaması ' . $i,
                    'category' => 'test',
                    'address' => 'Örnek Adres ' . $i,
                    'latitude' => 41.0082 + ($i * 0.01),
                    'longitude' => 28.9784 + ($i * 0.01),
                    'is_active' => true,
                ]);
            }
        }

        // Sahte Tasks (Görevler) oluştur (eğer yoksa)
        if (Task::count() == 0) {
            $auditPoints = AuditPoint::all();
            
            foreach ($auditPoints as $index => $auditPoint) {
                Task::create([
                    'title' => 'Görev ' . ($index + 1),
                    'description' => 'Bu görev, ' . $auditPoint->name . ' için otomatik oluşturuldu.',
                    'priority' => 'medium',
                    'audit_point_id' => $auditPoint->id,
                    'assigned_to' => $fieldPersonnel->id,
                    'assigned_manager' => $manager->id,
                    'status' => 'pending',
                    'due_date' => Carbon::now()->addDays(rand(1, 10)),
                ]);
            }
        }
    }
}
