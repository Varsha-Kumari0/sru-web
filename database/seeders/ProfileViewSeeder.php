<?php

namespace Database\Seeders;

use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return; // Skip if not enough users
        }

        foreach ($users as $profileUser) {
            $otherUsers = $users->where('id', '!=', $profileUser->id);
            
            if ($otherUsers->isEmpty()) continue;
            
            $viewCount = min(rand(1, 3), $otherUsers->count());
            $viewers = $otherUsers->random($viewCount);

            foreach ($viewers as $viewer) {
                ProfileView::create([
                    'profile_user_id' => $profileUser->id,
                    'visitor_user_id' => $viewer->id,
                    'viewed_at' => now()->subDays(rand(0, 60)),
                ]);
            }
        }
    }
}
