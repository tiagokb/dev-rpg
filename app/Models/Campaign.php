<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Campaign extends Model
{
    public const max_players = 3;

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
            do {
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $code = '';

                for ($i = 0; $i < 6; $i++) {
                    $code .= $characters[random_int(0, strlen($characters) - 1)];
                }
            } while (self::where('invite_code', $code)->exists());

            $campaign->invite_code = $code;
        });
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
        );
    }


    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('joined_at');
    }

    protected $appends = ['is_master'];
    public function getIsMasterAttribute(): bool
    {
        return $this->user_id === auth()->id();
    }
}
