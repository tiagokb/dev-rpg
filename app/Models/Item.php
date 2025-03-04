<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', // Nome do item
        'description', // Descrição do item
        'campaign_id' // ID da campanha associada
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}