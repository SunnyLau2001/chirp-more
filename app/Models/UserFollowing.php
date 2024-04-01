<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFollowing extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'following_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // The function name can be used in with() when querying
    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
