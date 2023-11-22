<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    // Отношение "один ко многим" с моделью Application
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
