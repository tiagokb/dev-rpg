<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Certifique-se de que o modelo Item existe


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos

        $item = Item::create([
            "campaign_id"=> $request->campaign_id,
            "name"=> $request->name,
            "classification"=> $request->classification,
            "description"=> $request->description,
            "damage"=> $request->damage,
            "magical_properties"=> $request->magical_properties,
            "classes"=> $request->classes,
            "weight"=> $request->weight,
            "image_url"=> $request->image_url,
            "rarity"=> $request->rarity,
            "type"=> $request->type,
            "price"=> $request->price,
        ]);

        // Redireciona para uma página específica ou retorna uma resposta JSON
        return back()->with('success', 'Item criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            return back()->with('success', 'Item deletado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao deletar item!');
        }
    }
}
