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
        'cause_fall',
    ];

    protected $casts = [
        'create_application' => 'datetime',
        'close_application' => 'datetime',
    ];

    public function Authors(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Executors(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
