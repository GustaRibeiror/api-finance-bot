<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\CategoryType;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'user_id'];

    protected $casts = [
        'type' => CategoryType::class,
    ];

    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class);
    }
}
