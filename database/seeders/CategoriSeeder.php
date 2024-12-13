<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categori;

class CategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categori::create([
            'name' => 'Sains Aktuaria',
            'slug' => 'sains-aktuaria',
            'description' => 'Berita seputar Fakultas Sains Aktuaria'
        ]);
    }
}
