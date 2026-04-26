<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillEndorsement;
use App\Models\User;
use Illuminate\Database\Seeder;

class SkillEndorsementSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $skills = Skill::all();

        if ($users->count() < 2 || $skills->count() < 1) {
            return; // Need at least 2 users and 1 skill
        }

        // Create endorsements for skills
        foreach ($skills as $skill) {
            $availableEndorsers = $users->where('id', '!=', $skill->user_id);

            if ($availableEndorsers->count() > 0) {
                $endorsementCount = rand(0, min(5, $availableEndorsers->count()));

                $endorsers = $availableEndorsers->random(min($endorsementCount, $availableEndorsers->count()));

                foreach ($endorsers as $endorser) {
                    SkillEndorsement::create([
                        'skill_id' => $skill->id,
                        'endorser_id' => $endorser->id,
                        'endorser_name' => $endorser->name,
                    ]);
                }

                // Update the endorsements_count on the skill
                $skill->update([
                    'endorsements_count' => $endorsementCount,
                ]);
            }
        }
    }
}