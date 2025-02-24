<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CampaignController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return Inertia::render('Campaigns/Index', [
            'campaigns' => $user->mastering()
                ->withCount('players')
                ->union($user->playing()->withCount('players'))
                ->paginate(10)
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
        $campaign = Campaign::with(['master', 'players'])->findOrFail($id);

        // Verificar se o usuário é mestre ou jogador
        if (auth()->id() !== $campaign->user_id && !$campaign->players->contains(auth()->id())) {
            abort(403, 'Acesso não autorizado');
        }

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

    public function leave(Request $request, Campaign $campaign)
{
    $user = auth()->user();

    // Verifica se o usuário realmente está na campanha
    if (!$campaign->players()->where('user_id', $user->id)->exists()) {
        return back()->withErrors(['error' => 'Você não faz parte desta campanha.']);
    }

    // Remove o jogador da campanha
    $campaign->players()->detach($user->id);

    return redirect()->route('campaigns.index')->with('success', 'Você saiu da campanha.');
}


    public function destroy(Campaign $campaign)
    {
        // Verifica se o usuário é o mestre da campanha
        if (auth()->id() !== $campaign->user_id) {
            abort(403, 'Acesso não autorizado');
        }

        // Exclui a campanha (os jogadores são removidos automaticamente via cascade)
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campanha excluída com sucesso!');
    }

}

