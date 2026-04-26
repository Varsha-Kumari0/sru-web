<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'title' => 'Early Bird',
                'description' => 'Joined the alumni network within first month of graduation',
                'badge_icon' => '🌅',
            ],
            [
                'title' => 'Profile Complete',
                'description' => 'Completed full profile with all details and skills',
                'badge_icon' => '✅',
            ],
            [
                'title' => 'Connector',
                'description' => 'Made 10 connections with alumni members',
                'badge_icon' => '🤝',
            ],
            [
                'title' => 'Tech Enthusiast',
                'description' => 'Added 5 or more technical skills',
                'badge_icon' => '💻',
            ],
            [
                'title' => 'Leadership',
                'description' => 'Held a leadership position after graduation',
                'badge_icon' => '🏆',
            ],
            [
                'title' => 'Startup Founder',
                'description' => 'Founded a startup or company',
                'badge_icon' => '🚀',
            ],
            [
                'title' => 'Mentor',
                'description' => 'Active mentor in the alumni community',
                'badge_icon' => '🎓',
            ],
            [
                'title' => 'Celebrated Career',
                'description' => 'Working at a Fortune 500 company',
                'badge_icon' => '⭐',
            ],
        ];

        $users = User::all();

        foreach ($users as $user) {
            $userAchievements = $achievements;
            shuffle($userAchievements);
            $count = rand(1, 4);

            for ($i = 0; $i < $count; $i++) {
                Achievement::create([
                    'user_id' => $user->id,
                    'title' => $userAchievements[$i]['title'],
                    'description' => $userAchievements[$i]['description'],
                    'badge_icon' => $userAchievements[$i]['badge_icon'],
                    'earned_at' => now()->subMonths(rand(0, 24)),
                ]);
            }
        }
    }
}
