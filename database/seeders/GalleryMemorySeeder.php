<?php

namespace Database\Seeders;

use App\Models\GalleryMemory;
use Illuminate\Database\Seeder;

class GalleryMemorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GalleryMemory::query()->delete();
    }
}