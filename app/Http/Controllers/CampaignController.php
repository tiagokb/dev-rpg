<?php

namespace App\Http\Controllers;

use Parsedown;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;



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
    $validated = $request->validate([
        'title' => 'required|string|max:255', // Título obrigatório
        'subtitle' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'cover_img_url' => 'nullable|url',
        'is_open' => 'nullable|boolean',
        'max_players' => 'nullable|integer|min:1'
    ]);

    // Gerar código de convite único
    $inviteCode = strtoupper(substr(md5(uniqid()), 0, 8));

    // Criar a campanha
    $campaign = Campaign::create([
        'title' => $validated['title'],
        'subtitle' => $validated['subtitle'] ?? null,
        'description' => $validated['description'] ?? null,
        'cover_img_url' => $validated['cover_img_url'] ?? null,
        'is_open' => $validated['is_open'] ?? true,
        'max_players' => $validated['max_players'] ?? 5, // Valor padrão
        'invite_code' => $inviteCode, // Código gerado
        'user_id' => auth()->id()
    ]);

    // Redirecionar para a página da campanha
    return redirect()->route('campaigns.show', $campaign->id)
        ->with('success', 'Campanha criada com sucesso!');
}

    public function view($id)
    {

        try {
            $campaign = Campaign::with(['master', 'players'])->findOrFail($id);
            Gate::authorize('view', $campaign);

            $parsedown = new Parsedown();
            $campaign->load(['npcs', 'items', 'notes']); // Carrega todos os relacionamentos
            $campaign->description_html = $parsedown->text($campaign->description);

            return Inertia::render('Campaigns/Details', ['campaign' => $campaign]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('campaigns.index')->with('error', 'Campanha não encontrada.');
        }
    }

    public function removePlayer(Request $request, Campaign $campaign, $player)
    {
        try {
            Gate::authorize('removePlayer', $campaign);
            if (!$campaign->players()->find($player)) {
                return back()->with('error', 'Jogador não encontrado na campanha.');
            }
    
            $campaign->players()->detach($player);
            return back()->with('success', 'Jogador removido com sucesso!');
    
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover jogador da campanha.');
        }
    }

    public function update(Request $request, Campaign $campaign)
    {

        try {
            $auth = Gate::authorize('update', $campaign);
            $campaign->update($request->only(['title', 'subtitle', 'is_open', 'cover_img_url', 'description']));
            return back()->with('success', $auth->message());
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function join(Request $request)
    {
        try {
            // Validação do código de convite
            $validated = $request->validate([
                'code' => 'required|string|size:10|regex:/^[A-Z0-9]{10}$/'
            ]);

            // Tenta encontrar a campanha
            $campaign = Campaign::where('invite_code', $validated['code'])->firstOrFail();

            // Autorização para entrar na campanha
            $auth = Gate::authorize('join', $campaign);

            // Adiciona o jogador à campanha
            $campaign->players()->attach(auth()->id(), ['joined_at' => now()]);

            return redirect()->route('campaigns.index')->with('success', $auth->message());
        } catch (ModelNotFoundException $e) {
            // Trata o caso em que a campanha não é encontrada
            return redirect()->route('campaigns.index')->with('error', 'Código de convite inválido ou campanha não encontrada.');
        } catch (\Exception $e) {
            // Trata outros erros
            return redirect()->route('campaigns.index')->with('error', $e->getMessage());
        }
    }

    public function leave(Request $request, Campaign $campaign)
    {
        try {

            if (!$campaign) {
                return redirect()->route('campaigns.index')->with('error', 'Esta campanha não existe mais.');
            }

            $auth = Gate::authorize('leave', $campaign);
            $campaign->players()->detach(auth()->user()->id);

            return redirect()->route('campaigns.index')->with('success', $auth->message());

        } catch (ModelNotFoundException $e) {
            // Trata o caso em que a campanha não é encontrada
            return redirect()->route('campaigns.index')->with('error', 'Esta campanha não existe mais.');
        } catch (\Exception $e) {
            // Captura qualquer outro erro inesperado
            return redirect()->route('campaigns.index')->with('error', 'Ocorreu um erro ao sair da campanha.');
        }
    }

    public function destroy(Campaign $campaign)
    {
        try {
            $auth = Gate::authorize('destroy', $campaign);
            $campaign->delete();
            return redirect()->route('campaigns.index')->with('success', $auth->message());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function transfer(Request $request, Campaign $campaign)
    {
        try {

            $auth = Gate::authorize('transfer', $campaign);

            // Verifica se o novo mestre é um jogador da campanha
            $newMaster = $campaign->players()->find($request->input('new_master_id'));

            if ($campaign->user_id == $request->input('new_master_id')) {
                return back()->with('error', 'O novo mestre deve ser diferente do mestre atual.');
            }

            if (!$newMaster) {
                return back()->with('error', 'O novo mestre deve ser um jogador da campanha.');
            }

            $campaign->user_id = $newMaster->id;
            $campaign->players()->attach(auth()->id(), ['joined_at' => now()]);
            $campaign->players()->detach($newMaster->id);
            $campaign->save();

            return back()->with('success', $auth->message());
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao transferir!');
        }
    }
}
