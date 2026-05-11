<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Enums\FromType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{   

    protected $fillable = ['description', 'amount', 'type', 'user_id', 'category_id'];
    
    protected $casts = [
        'type' =>  CategoryType::class,
        'amount' => 'integer',
    ];
    
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
