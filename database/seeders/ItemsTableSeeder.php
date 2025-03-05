<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'name' => 'Machado Flamejante do Rei Dragão',
                'description' => 'Lâmina forjada em núcleo vulcânico, com runas que brilham como lava.',
                'image_url' => '/items/machado_flamejante.png',
                'magical_properties' => 'Inflige +2d6 de dano de fogo e ignora resistência a fogo',
                'price' => 5500.00,
                'weight' => 3.50,
                'rarity' => 'Lendária',
                'type' => 'Arma',
                'classification' => 'Marcial',
                'classes' => 'Guerreiro, Paladino',
                'damage' => '2d6 de fogo + 1d8 cortante',
                'campaign_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cajado da Lua Etérea',
                'description' => 'Cajado de madeira petrificada incrustado com fragmentos de meteorito lunar.',
                'image_url' => '/items/cajado_daluz.png',
                'magical_properties' => 'Concede +2 em testes de conjuração e 1 carga/dia do feitiço *Armadura Lunar*',
                'price' => 3200.00,
                'weight' => 1.80,
                'rarity' => 'Raro',
                'type' => 'Arma',
                'classification' => 'Simples',
                'classes' => 'Mago, Feiticeiro',
                'damage' => '1d8 concussão mágica',
                'campaign_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anel da Proteção Sombria',
                'description' => 'Anel de obsidiana com inscrições de uma língua antiga esquecida.',
                'image_url' => '/items/anel_protecao.png',
                'magical_properties' => 'Resistência a dano necrótico e 1x/dia pode se tornar invisível por 1d4 rodadas',
                'price' => 1800.00,
                'weight' => 0.10,
                'rarity' => 'Incomum',
                'type' => 'Anel',
                'classification' => 'Mágico',
                'classes' => 'Ladino, Bruxo',
                'damage' => null,
                'campaign_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manto do Viajante Dimensional',
                'description' => 'Manto negro que parece conter constelações em seu tecido.',
                'image_url' => '/items/manto_viajante.png',
                'magical_properties' => '1x/dia pode teleportar até 30m e reduz peso carregado em 20%',
                'price' => 4200.00,
                'weight' => 0.75,
                'rarity' => 'Raro',
                'type' => 'Vestuário',
                'classification' => 'Mágico',
                'classes' => 'Qualquer',
                'damage' => null,
                'campaign_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Poção da Cura Ancestral',
                'description' => 'Líquido âmbar que emite pulsos suaves de luz dourada.',
                'image_url' => '/items/potion_hp.png',
                'magical_properties' => 'Restaura 4d8+4 PV e remove 1 condição de status aleatória',
                'price' => 750.00,
                'weight' => 0.25,
                'rarity' => 'Incomum',
                'type' => 'Consumível',
                'classification' => 'Poção',
                'classes' => 'Qualquer',
                'damage' => null,
                'campaign_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}