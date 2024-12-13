<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\categori;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'username' => 'Admin ITESA',
            'email' => 'admin@itesa.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'bio' => 'Administrator Portal Berita ITESA',
        ]);

        $categories = [
            ['name' => 'Berita', 'slug' => 'berita'],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman'],
            ['name' => 'Event', 'slug' => 'event'],
            ['name' => 'Prodi', 'slug' => 'prodi'],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan'],
            ['name' => 'Olah Raga', 'slug' => 'olahraga'],
            ['name' => 'Sains Aktuaria', 'slug' => 'sains-aktuaria'],
            ['name' => 'Teknologi Informasi', 'slug' => 'teknologi-informasi'],
            ['name' => 'Manajemen Retail', 'slug' => 'manajemen-retail'],
            ['name' => 'Rekayasa Perangkat Lunak', 'slug' => 'rekayasa-perangkat-lunak'],
            ['name' => 'Management Retail', 'slug' => 'management-retail'],
            ['name' => 'Statistika', 'slug' => 'statistika'],
        ];

        foreach ($categories as $category) {
            categori::create($category);
        }

    }
}
