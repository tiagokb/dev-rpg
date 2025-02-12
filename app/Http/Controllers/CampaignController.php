<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CampaignController extends Controller

{
    public function index(){
        $campaigns = auth()->user()->campaigns()->with('master')->get();
        
        return Inertia::render('Campaigns/Index', ['campaigns' => $campaigns]);


        // return Inertia::render('Dashboard', ['campaigns' => $campaigns, 'links' => $campaigns->links()]);
        
        // Lógica para obter as campanhas
        // $campaigns = Campaign::where('user_id', Auth::id())->with('master')->get();
        // dd($campaigns->toRawSql());
        // $campaigns = auth()->user()->campaigns()->with('master')->paginate(2);

        // $campaigns2 = auth()->user()->campaigns()->with('master')->get();
        
        // Retorna a view com as campanhas
        // dd($campaigns->toArray(), $campaigns->links());

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

        return redirect()->route('campaigns.index')->with('success', 'Campanha criada com sucesso!');

            //     return redirect()->route('campaigns.index')
            // ->with('success', 'Campanha criada com sucesso!');
    }

    public function edit($id){
        // Lógica para obter a campanha
        $campaign = Campaign::with(['master', 'players'])->get()->find($id);

        // Retorna a view com a campanha
        return Inertia::render('Campaigns/Details', ['campaign' => $campaign]);
    }




}

