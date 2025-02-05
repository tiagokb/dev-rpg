<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'user_id',
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('joined_at');
    }
}
