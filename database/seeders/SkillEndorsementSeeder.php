<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillEndorsement;
use Illuminate\Database\Seeder;

class SkillEndorsementSeeder extends Seeder
{
    public function run(): void
    {
        SkillEndorsement::query()->delete();
        Skill::query()->update([
            'endorsements' => 0,
            'endorsements_count' => 0,
        ]);
    }
}
