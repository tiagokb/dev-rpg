<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CampaignController extends Controller
{
    // public function index()
    // {
    //     $campaigns = auth()->user()->campaigns()->get();

    //     return Inertia::render('Campaigns/Index', ['campaigns' => $campaigns]);

    //     // return Inertia::render('Dashboard', ['campaigns' => $campaigns, 'links' => $campaigns->links()]);

    //     // Lógica para obter as campanhas
    //     // $campaigns = Campaign::where('user_id', Auth::id())->with('master')->get();
    //     // dd($campaigns->toRawSql());
    //     // $campaigns = auth()->user()->campaigns()->with('master')->paginate(2);

    //     // $campaigns2 = auth()->user()->campaigns()->with('master')->get();

    //     // Retorna a view com as campanhas
    //     // dd($campaigns->toArray(), $campaigns->links());

    // }

    public function index()
{
    $user = auth()->user();
    
    $campaigns = Campaign::query()
        ->where(function($query) use ($user) {
            $query->where('user_id', $user->id) // Campanhas onde é mestre
                  ->orWhereHas('players', function($q) use ($user) {
                      $q->where('user_id', $user->id); // Campanhas onde é jogador
                  });
        })
        ->with(['master', 'players'])
        ->paginate(10);

    return Inertia::render('Campaigns/Index', [
        'campaigns' => $campaigns,
        'userRole' => [
            'isMaster' => $user->masteringCampaign->pluck('id'),
            'isPlayer' => $user->playingCampaign->pluck('id')
        ]
    ]);
}

    public function store(Request $request)
    {
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

    public function edit($id)
    {
        // Lógica para obter a campanha
        $campaign = Campaign::with(['master', 'players'])->get()->find($id);

        // Retorna a view com a campanha
        return Inertia::render('Campaigns/Details', ['campaign' => $campaign]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $campaign->update($request->only(['name', 'image_url', 'description']));
    }

    public function join(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6|regex:/^[A-Z0-9]{6}$/'
        ]);

        $campaign = Campaign::where('invite_code', $validated['code'])->firstOrFail();

        if ($campaign->players()->where('user_id', auth()->id())->exists()) {
            return back()->withErrors(['code' => 'Você já está nesta campanha']);
        }

        $campaign->players()->attach(auth()->id(), ['joined_at' => now()]);

        return redirect()->route('campaigns.index')->with('success', 'Você entrou na campanha com sucesso!');
    }


}

