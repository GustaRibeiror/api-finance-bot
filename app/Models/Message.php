<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['content', 'from', 'user_id'];

    //protected $casts = ['$this->from = ENUM::class']; - chutando pq não sei como fazer

    // protected function casts(): array
    // {
    //     return [
    //         'from' => ENUM::class,
    //     ];
    // } outra tentativa

    public function users(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
