<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'level',
        'endorsements_count',
    ];

    protected $casts = [
        'endorsements_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function endorsements(): HasMany
    {
        return $this->hasMany(SkillEndorsement::class);
    }

    public function getLevelColorAttribute(): string
    {
        return match($this->level) {
            'beginner' => 'bg-green-100 text-green-800',
            'intermediate' => 'bg-yellow-100 text-yellow-800',
            'advanced' => 'bg-orange-100 text-orange-800',
            'expert' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getLevelTextAttribute(): string
    {
        return ucfirst($this->level ?? 'beginner');
    }
}
