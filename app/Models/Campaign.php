<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Observers\CampaignObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([CampaignObserver::class])]


class Campaign extends Model
{

    protected $fillable = [
        'title', // Título principal
        'subtitle', // Subtítulo
        'description', // Descrição geral
        'cover_img_url', // URL da imagem de capa
        'max_players', // Número máximo de jogadores
        'is_open', // Status de abertura (true/false)
        'user_id' // ID do mestre
    ];

    protected $casts = [
        'is_open' => 'boolean', // Define o campo como booleano
    ];

    protected $appends = ['is_master'];


    public function master(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['joined_at']);
    }

    // Relacionamento com os itens da campanha
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    // Relacionamento com os NPCs da campanha
    public function npcs(): HasMany
    {
        return $this->hasMany(Npc::class);
    }

    // Relacionamento com as notas da campanha
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
        );
    }

    public function getIsMasterAttribute(): bool
    {
        return $this->user_id === auth()->id();
    }

    public function contents()
    {
        return $this->hasMany(CampaignContent::class);
    }
}
