<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampaignController extends Controller
{

    /*
     *
     * Aqui é onde devemos renderizar a tela onde será mostrado uma lista de campanhas do usuário.
     *
     */
    public function index()
    {
        $user = auth()->user();

        $imMaster = $user->masteringCampaign();
        $imPlaying = $user->playingCampaign();

        $campaigns = [                  // O formato ira para o front como um objeto do js
            'mastering' => $imMaster,
            'playing' => $imPlaying
        ];

//        return Inertia::render('Campaigns/index', ['campaigns' => $campaigns]); //TODO: Criar rotas e views
    }

    public function show($id)
    {
        $campaign = Campaign::find($id);
//        return Inertia::render('Campaign/show', [
//            'campaign' => $campaign
//        ]);
    }

    // Método para exibir o formulário de criação
    public function create()
    {
//        return Inertia::render('Campaigns/Create');
    }

    // Método para salvar a campanha
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        // Criar a campanha
        $campaign = Campaign::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image_url' => $request->input('image_url'),
            'master_id' => auth()->id(), // Usuário logado é o mestre
        ]);

        // Redirecionar para a página de detalhes da campanha ou outra rota
//        return redirect()->route('campaigns.show', $campaign->id)
//            ->with('success', 'Campanha criada com sucesso!');
    }
}
