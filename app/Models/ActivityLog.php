<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_user_id',
        'subject_user_id',
        'action',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public static function record(
        ?int $actorUserId,
        ?int $subjectUserId,
        string $action,
        string $description,
        array $properties = []
    ): self {
        return self::create([
            'actor_user_id' => $actorUserId,
            'subject_user_id' => $subjectUserId,
            'action' => $action,
            'description' => $description,
            'properties' => empty($properties) ? null : $properties,
        ]);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function subject()
    {
        return $this->belongsTo(User::class, 'subject_user_id');
    }
}