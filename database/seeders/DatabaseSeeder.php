<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Bid;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::create([
            'name' => 'Administrator Garuda',
            'email' => 'admin@garudahub.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Klien
        $klien1 = User::create([
            'name' => 'Budi Santoso (Klien)',
            'email' => 'budi@garudahub.test',
            'password' => Hash::make('password123'),
            'role' => 'klien',
        ]);

        $klien2 = User::create([
            'name' => 'Siti Rahma (Klien)',
            'email' => 'siti@garudahub.test',
            'password' => Hash::make('password123'),
            'role' => 'klien',
        ]);

        // 3. Akun Developer
        $dev1 = User::create([
            'name' => 'Andi Pratama (Dev)',
            'email' => 'andi@garudahub.test',
            'password' => Hash::make('password123'),
            'role' => 'developer',
        ]);

        $dev2 = User::create([
            'name' => 'Rina Wijaya (Dev)',
            'email' => 'rina@garudahub.test',
            'password' => Hash::make('password123'),
            'role' => 'developer',
        ]);

        // 4. Proyek Dummy
        $p1 = Project::create([
            'client_id' => $klien1->id,
            'title' => 'Pembuatan Website E-Commerce Toko Pakaian',
            'description' => 'Dibutuhkan website e-commerce responsif dengan sistem katalog produk, integrasi payment gateway, dan panel admin inventaris.',
            'category' => 'Web Development',
            'budget_min' => 5000000,
            'budget_max' => 10000000,
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'terbuka',
        ]);

        $p2 = Project::create([
            'client_id' => $klien2->id,
            'title' => 'Aplikasi Mobile Presensi Karyawan Berbasis GPS',
            'description' => 'Aplikasi Android & iOS Flutter untuk pencatatan kehadiran karyawan dengan geofencing dan face verification.',
            'category' => 'Mobile App',
            'budget_min' => 8000000,
            'budget_max' => 15000000,
            'deadline' => now()->addDays(14)->toDateString(),
            'status' => 'terbuka',
        ]);

        $p3 = Project::create([
            'client_id' => $klien1->id,
            'title' => 'Desain Ulang UI/UX Dashboard Analytics',
            'description' => 'Membuat design system Figma dan rancangan antarmuka modern untuk SaaS analitik data keuangan.',
            'category' => 'UI/UX Design',
            'budget_min' => 3000000,
            'budget_max' => 6000000,
            'deadline' => now()->subDays(2)->toDateString(),
            'status' => 'ditutup',
        ]);

        // 5. Penawaran Dummy (Bids)
        Bid::create([
            'project_id' => $p1->id,
            'developer_id' => $dev1->id,
            'price' => 7500000,
            'duration_days' => 20,
            'proposal' => 'Saya berpengalaman 4 tahun membangun toko online dengan Laravel dan Vue.js. Siap menyelesaikan tepat waktu.',
            'is_winner' => false,
        ]);

        Bid::create([
            'project_id' => $p1->id,
            'developer_id' => $dev2->id,
            'price' => 6500000,
            'duration_days' => 15,
            'proposal' => 'Portofolio e-commerce saya lengkap dan siap pakai. Menawarkan garansi bug-fix selama 2 bulan setelah peluncuran.',
            'is_winner' => false,
        ]);

        Bid::create([
            'project_id' => $p3->id,
            'developer_id' => $dev2->id,
            'price' => 4500000,
            'duration_days' => 7,
            'proposal' => 'Desain modern, komponen Figma rapi auto-layout, siap diserahkan ke tim frontend developer.',
            'is_winner' => true,
        ]);
    }
}
