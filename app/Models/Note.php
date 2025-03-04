<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'title', // Título da nota
        'content', // Conteúdo da nota
        'campaign_id' // ID da campanha associada
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}