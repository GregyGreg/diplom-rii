<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'text_application',
        'create_application',
        'close_application',
        'executor_id',
        'status',
        'category_id',
        'commentary',
    ];

    protected $casts = [
        'create_application' => 'datetime',
        'close_application' => 'datetime',
    ];

    // Отношение "многие к одному" с таблицей Users
    public function Authors(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Отношение "многие к одному" с таблицей Users
    public function Executors(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executor_id', relation: 'executorApplications');
    }

    // Отношение "многие к одному" с таблицей Categories
    public function Categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
