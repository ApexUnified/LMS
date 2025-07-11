<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable // implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
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

    protected $appends = ['avatar', 'role_id', 'profile_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function getProfileUrlAttribute()
    {
        return ! empty($this->attributes['profile']) ? asset('assets/images/user/'.$this->attributes['profile']) : null;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRoleIdAttribute()
    {
        return ! blank($this->roles) ? $this?->roles?->pluck('id')?->implode('') : null;
    }

    public function getAvatarAttribute()
    {
        $name = $this->name;

        $parts = explode(' ', trim($name));

        $first = isset($parts[0]) ? strtoupper(substr($parts[0], 0, 1)) : '';

        $last = isset($parts[1]) ? strtoupper(substr(end($parts), 0, 1)) : '';

        // Return initials
        return $first.$last;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification);
    }

    // Relations
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id', 'id');
    }

    public function lessonProgressUser(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'user_id', 'id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }
}
