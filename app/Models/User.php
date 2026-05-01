<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }


    /**
     * Get the user's professional record.
     */
    public function professional(): HasOne
    {
        return $this->hasOne(Professional::class);
    }

    /**
     * Get job and internship opportunities posted by the user.
     */
    public function jobOpportunities(): HasMany
    {
        return $this->hasMany(JobOpportunity::class);
    }

    /**
     * Get the user's skills.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function getDisplayNameAttribute(): string
    {
        $name = trim((string) $this->name);
        $profileName = trim((string) ($this->profile?->full_name ?? ''));

        if ($profileName !== '' && in_array($name, ['Alumni User', 'User'], true)) {
            return $profileName;
        }

        return $name !== '' ? $name : ($profileName !== '' ? $profileName : 'Unknown User');
    }
}
