<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name' => 'string',
        'last_name',
        'surname',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFormattedNameAttribute(): string
    {
        $name = $this->name;
        $surname = $this->surname;
        $last_name = $this->last_name;

        $initials = $last_name . ' ' . mb_substr($name, 0, 1, 'UTF-8') . '.' . mb_substr($surname, 0, 1, 'UTF-8') . '.';

        return $initials;
    }

    public function authorApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'author_id');
    }

    public function executorApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'executor_id');
    }
}
