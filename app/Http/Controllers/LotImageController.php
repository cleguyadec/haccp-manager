<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\LotPhoto;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class LotImageController extends Controller
{
    public function manage(Lot $lot)
    {
        $photos = $lot->photos;
        return view('lots.images.manage', compact('lot', 'photos'));
    }

    public function store(Request $request, Lot $lot)
    {
        // Validation
        $validated = $request->validate([
            'photos.*' => 'required|image|max:10240', // Maximum 2MB par image
        ]);
    
        foreach ($request->file('photos') as $photo) {
            // Réduire et compresser l'image
            $image = Image::make($photo);
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio(); // Conserver les proportions
            })->encode('jpg', 75); // Compresser à 75% de qualité
    
            // Générer un chemin pour l'image
            $path = 'photos/' . uniqid() . '.jpg';
    
            // Sauvegarder l'image dans le système de fichiers
            Storage::disk('public')->put($path, $image);
    
            // Enregistrer dans la base de données
            $lot->photos()->create([
                'photo_path' => $path,
            ]);
        }
    
        return back()->with('success', 'Images ajoutées avec succès.');
    }

    public function destroy(LotPhoto $image)
    {
        $image->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }
}
