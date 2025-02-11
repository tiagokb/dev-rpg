<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CampaignController extends Controller

{
    public function dashboard()
    {
        // Lógica para obter as campanhas
        $campaigns = Campaign::all();

        // Retorna a view com as campanhas
        return Inertia::render('Dashboard', ['campaigns' => $campaigns]);
    }

    public function create()
    {
        // Retorna a página de criação de campanha
        return Inertia::render('Campaigns/Create');
    }

    
    public function store(Request $request){
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);

        // Criar a campanha
        $campaign = Campaign::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image_url' => $request->input('image_url'),
            'user_id' => auth()->id(), // Usuário logado é o mestre
        ]);

        return redirect()->route('dashboard')->with('success', 'Campanha criada com sucesso!');

            //     return redirect()->route('campaigns.index')
            // ->with('success', 'Campanha criada com sucesso!');
    }
}

