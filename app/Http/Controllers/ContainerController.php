<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Container;
use Illuminate\Http\Request;

class ContainerController extends Controller
{
    public function manage()
    {
        $containers = Container::all(); // Récupérer tous les contenants
        return view('containers.manage', compact('containers'));
    }

    public function store(Request $request)
    {
        $request->validate(['size' => 'required|string|max:255']);
        Container::create($request->all());
        return redirect()->route('containers.manage')->with('success', 'Contenant ajouté avec succès.');
    }

    public function update(Request $request, Container $container)
    {
        $request->validate(['size' => 'required|string|max:255']);
        $container->update($request->all());
        return redirect()->route('containers.manage')->with('success', 'Contenant mis à jour avec succès.');
    }

    public function destroy(Container $container)
    {
        $container->delete();
        return redirect()->route('containers.manage')->with('success', 'Contenant supprimé avec succès.');
    }

    public function destroyWithReplacement(Request $request)
{
    $containerId = $request->input('container_id');
    $replacementId = $request->input('replacement_id');

    // Vérifiez que les deux contenants existent
    $container = Container::findOrFail($containerId);
    $replacement = Container::findOrFail($replacementId);

    // Mettre à jour les produits pour utiliser le contenant de remplacement
    Product::where('container_id', $container->id)
           ->update(['container_id' => $replacement->id]);

    // Supprimer le contenant initial
    $container->delete();

    return response()->json(['message' => 'Contenant supprimé avec succès.'], 200);
}

}
