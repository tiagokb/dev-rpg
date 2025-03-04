<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Npc extends Model
{
    protected $fillable = [
        'name', // Nome do NPC
        'description', // Descrição do NPC
        'campaign_id' // ID da campanha associada
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}