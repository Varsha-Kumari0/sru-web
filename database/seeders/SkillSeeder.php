<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            'Laravel',
            'PHP',
            'JavaScript',
            'React',
            'Python',
            'Machine Learning',
            'Data Analysis',
            'Cloud Computing',
            'Docker',
            'AWS',
            'Git',
            'SQL',
            'REST APIs',
            'UI/UX Design',
            'Project Management',
        ];

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            $userSkills = $skills;
            shuffle($userSkills);
            $count = rand(3, 7);

            for ($i = 0; $i < $count; $i++) {
                Skill::create([
                    'user_id' => $user->id,
                    'name' => $userSkills[$i],
                    'endorsements' => rand(0, 15),
                ]);
            }
        }
    }
}
