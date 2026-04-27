<?php

namespace Database\Seeders;

use App\Models\GalleryVideo;
use Illuminate\Database\Seeder;

class GalleryVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GalleryVideo::query()->delete();
    }
}