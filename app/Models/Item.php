<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'campaign_id', // ID da campanha associada
        'name', // Nome do item
        'description', // Descrição do item
        'classification', // Classificação do item
        'damage', // Dano causado pelo item
        'magical_properties', // Propriedades mágicas do item
        'classes', // Classes que podem usar o item
        'weight', // Peso do item
        'image_url', // URL da imagem do item
        'rarity', // Raridade do item
        'type', // Tipo do item
        'price', // Preço do item

    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}