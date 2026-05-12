<?php

namespace App\Models;

use App\Enums\FromType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['content', 'from', 'user_id'];

    protected $casts = [
        'from' => FromType::class,
    ];

    public function users(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
