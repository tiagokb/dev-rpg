<?php

namespace App\Http\Controllers;

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
    }

    public function view($id)
    {

        try {
            $campaign = Campaign::with(['master', 'players'])->findOrFail($id);
            Gate::authorize('view', $campaign);
            return Inertia::render('Campaigns/Details', ['campaign' => $campaign]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('campaigns.index')->with('error', 'Campanha não encontrada.');
        }
    }

    public function update(Request $request, Campaign $campaign)
    {

        try {
            $auth = Gate::authorize('update', $campaign);
            $campaign->update($request->only(['name', 'image_url', 'description']));
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
