<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CampaignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insere usuários iniciais
        DB::table('campaigns')->insert([
            [
                'id' => 1,
                'title' => 'Aventura Inicial',
                'subtitle' => 'Uma aventura para iniciantes',
                'description' => '## A Maldição do Crepúsculo Esquecido
*Uma campanha de horror cósmico e segredos ancestrais*  

---

## 🗺️ Mapa do Vale do Crepúsculo 
![](https://cdn.leonardo.ai/users/480274db-09a5-4820-8b88-b211a424a0f6/generations/d9f1cc73-6d7d-4954-b639-e9d218a5ea34/AlbedoBase_XL_old_map_regions_on_ficcional_planet_rpg_dungeons_3.jpg)

---

## 🏰 5 Localizações Principais 

1. **Cidade de Sombrália**  
   - Último bastião da civilização, governada pela fanática *Ordem da Lua Pálida*. Torres de pedra negra abrigam um portal para o Submundo.  

2. **Floresta dos Sussurros**  
   - Árvores distorcem vozes dos viajantes. No centro, o *Círculo de Pedras de Elarion* guarda um antigo pacto com entidades cósmicas.  

3. **Abismo de Vorath**  
   - Fenda vulcânica onde cultistas realizam sacrifícios. O calor derrete até metais, e lendas falam de um *dragão de magma adormecido*.  

4. **Vila Afogada de Nethra**  
   - Aldeia submersa em um lago, habitada por espectros que repetem o dia de sua destruição. Um *espelho no fundo do lago* mostra visões do passado.  

5. **Torre do Cronófago**  
   - Ruína flutuante onde o tempo acelera ou desacelera aleatoriamente. O tomo proibido *"O Último Suspiro de Azathoth"* está selado aqui.  

---

## 🌌 Eventos Importantes  

1. **O Chamado das Estrelas** *(Nível 1-3)*  
   > *"Vocês veem padrões nas constelações... como se algo vos observasse de além do véu."*  
   - Os jogadores descobrem que sonhos compartilhados são *mensagens de uma entidade aprisionada*.  

2. **A Ceira dos Três Sóis** *(Nível 4-6)*  
   > *"O eclipse não é um fenômeno natural—é um olho."*  
   - Um ritual nas ruínas de Elarion liberta criaturas feitas de pura escuridão (*Estigmares*).  

3. **O Julgamento do Espelho** *(Nível 7-9)*  
   > *"Para enfrentar o Cronófago, primeiro enfrentem a si mesmos."*  
   - Na Vila Afogada, os jogadores revivem traumas passados e descobrem um traidor no grupo.  

4. **O Despertar de Vorath** *(Nível 10-12)*  
   > *"A terra sangra, e seu coração bate sob nossos pés!"*  
   - O dragão de magma emerge, revelando ser um *avatar do Devorador de Eras*.  

5. **O Último Crepúsculo** *(Nível 13-15)*  
   > *"Rompam o ciclo... ou tornem-se parte dele."*  
   - Escolha final: destruir o portal em Sombrália (matando milhares) ou substituir a entidade aprisionada (corrompendo um jogador).  

---

## 🎭 Citações Marcantes  
- *"A luz que vocês chamam de esperança é apenas o reflexo tardio de estrelas mortas."* — **Véritas**, espectro da Vila Afogada.  
- *"O tempo é uma faca. Cortem na direção certa."* — Gravação nas paredes da Torre do Cronófago.  

---

## ✨ Como Usar Esta História  
- **Plot Twist:** A Ordem da Lua Pálida está *alimentando* a entidade para manter seu poder.  
- **NPCs Memoráveis:** Um mercador que vende "lembranças roubadas" em frascos, uma criança que prevê mortes em desenhos.  
- **Temas Centrais:** Ciclos de destruição/criação, o preço da imortalidade, e a ilusão do livre arbítrio.',
                'cover_img_url' => 'https://cdn.leonardo.ai/users/480274db-09a5-4820-8b88-b211a424a0f6/generations/01a49595-ec9a-4b41-a834-285710c75253/AlbedoBase_XL_Enveloped_in_a_shimmering_dome_of_magical_energy_1.jpg',
                'user_id' => 1,
                'is_open' => false,
                'max_players' => 5,
                'invite_code' => '1234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}