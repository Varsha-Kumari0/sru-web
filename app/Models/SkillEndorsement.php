<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillEndorsement extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_id',
        'endorser_id',
        'endorser_name',
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function endorser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'endorser_id');
    }
}