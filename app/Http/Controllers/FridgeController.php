<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\TemperatureImage;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;

class FridgeController extends Controller
{
    public function index()
    {
        $fridges = Fridge::with('temperatures')->get();
        return view('fridges.index', compact('fridges'));
    }

    public function create()
    {
        return view('fridges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Fridge::create($validated);
        return redirect()->route('fridges.index')->with('success', 'Frigo ajouté avec succès.');
    }

    public function show(Fridge $fridge, Request $request)
    {
        $query = $fridge->temperatures();
    
        // Appliquer les filtres de date si fournis
        if ($request->filled('start_date')) {
            $query->whereDate('captured_at', '>=', $request->start_date);
        }
    
        if ($request->filled('end_date')) {
            $query->whereDate('captured_at', '<=', $request->end_date);
        }
    
        $temperatures = $query->orderBy('captured_at', 'desc')->get();
    
        return view('fridges.show', [
            'fridge' => $fridge,
            'temperatures' => $temperatures,
        ]);
    }

    public function edit(Fridge $fridge)
{
    return view('fridges.edit', compact('fridge'));
}

    public function update(Request $request, Fridge $fridge)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $fridge->update($request->only(['name', 'location']));

        return redirect()->route('fridges.index')->with('success', 'Frigo mis à jour avec succès.');
    }

    

    public function manage()
    {
        $fridges = Fridge::all(); // Exemple : récupérer tous les frigos
        return view('fridges.index', compact('fridges'));
    }

    public function uploadTemperature(Request $request, Fridge $fridge)
    {
        // Validation de la requête
        $validated = $request->validate([
            'image' => 'required|image|max:10240', // Accepte jusqu'à 10 Mo
        ]);
    
        // Chemin pour le stockage de l'image
        $path = $request->file('image')->store('temperatures', 'public');
    
        // Compression de l'image
        $imagePath = storage_path('app/public/' . $path);
        $image = Image::make($imagePath)->resize(800, null, function ($constraint) {
            $constraint->aspectRatio(); // Maintient les proportions
        })->encode('jpg', 75); // Compresse avec 75% de qualité
    
        // Sauvegarde l'image compressée
        $image->save($imagePath);
    
        // Création d'un nouvel enregistrement de température
        $temperature = new TemperatureImage();
        $temperature->fridge_id = $fridge->id;
        $temperature->image_path = $path;
        $temperature->captured_at = now();
    
        // Si une extraction OCR est disponible, ajoutez la température
        if (function_exists('extractTemperatureFromImage')) {
            $temperature->temperature = extractTemperatureFromImage($path);
        }
    
        $temperature->save();
    
        return back()->with('success', 'Température ajoutée avec succès.');
    }
    
    // Méthode pour supprimer un frigo
    public function destroy(Fridge $fridge)
    {
        try {
            $fridge->delete();
            return redirect()->route('fridges.index')->with('success', 'Frigo supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('fridges.index')->with('error', 'Une erreur est survenue lors de la suppression du frigo.');
        }
    }

    // Fonction pour extraire la température via OCR
    private function extractTemperatureFromImage($imagePath)
    {
        try {
            // Utilisation de Tesseract pour lire le texte
            $ocr = new TesseractOCR($imagePath);
            $text = $ocr->run();

            // Extraire les températures du texte avec une regex
            if (preg_match('/-?\d+(\.\d+)?/', $text, $matches)) {
                return $matches[0]; // Retourne la température trouvée
            }

            return null; // Si aucune température n'est détectée
        } catch (\Exception $e) {
            report($e); // Reporter les erreurs dans les logs
            return null;
        }
    }

    public function deleteTemperature($id)
    {
        $temperature = TemperatureImage::findOrFail($id);

        // Supprime l'image du stockage
        Storage::delete($temperature->image_path);

        // Supprime l'entrée dans la base de données
        $temperature->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }

}

