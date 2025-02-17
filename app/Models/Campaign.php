<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaign) {
            $campaign->invite_code = Str::uuid(); // Gera o UUID
        });
    }
    

    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('joined_at');
    }

}