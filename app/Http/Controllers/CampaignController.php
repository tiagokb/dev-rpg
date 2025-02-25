<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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

    }

    public function view($id)
    {
        $campaign = Campaign::with(['master', 'players'])->findOrFail($id);
        // dd($campaign->created_at, $campaign->players()->first()->pivot->joined_at, $campaign->updated_at);
        // dd($campaign->players()->first()->pivot, $campaign);

        Gate::authorize('view', $campaign);
        return Inertia::render('Campaigns/Details', ['campaign' => $campaign]);
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
            $validated = $request->validate([
                'code' => 'required|string|size:10|regex:/^[A-Z0-9]{10}$/'
            ]);

            $campaign = Campaign::where('invite_code', $validated['code'])->firstOrFail();

            $auth = Gate::authorize('join', $campaign);

            $campaign->players()->attach(auth()->id(), ['joined_at' => now()]);
            return redirect()->route('campaigns.index')->with('success', $auth->message());
        } catch (\Exception $e) {
            return redirect()->route('campaigns.index')->with('error', $e->getMessage());
        }
    }

    public function leave(Request $request, Campaign $campaign)
    {
        try {

            $auth = Gate::authorize('leave', $campaign);
            $campaign->players()->detach(auth()->user()->id);
            return redirect()->route('campaigns.index')->with('success',  $auth->message());
        } catch (\Exception $e) {
            return redirect()->route('campaigns.index')->with('error', $e->getMessage());
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
}
