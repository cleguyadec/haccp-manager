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
            'photos.*' => 'required|image|max:10240', // Maximum 10MB par image
        ]);
    
        foreach ($request->file('photos') as $photo) {
            // Réduire et compresser l'image
            $image = Image::make($photo);
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio(); // Conserver les proportions
            })->encode('jpg', 75); // Compresser à 75% de qualité
    
            // Générer un nom unique pour l'image
            $filename = uniqid() . '.jpg';
    
            // Chemin où l'image sera sauvegardée
            $path = 'storage/photos/' . $filename;
    
            // Sauvegarder l'image dans le dossier public/storage/photos
            $fullPath = public_path($path);
            $image->save($fullPath);
    
            // Enregistrer dans la base de données
            $lot->photos()->create([
                'photo_path' => 'photos/' . $filename, // Chemin relatif à `public/storage`
            ]);
        }
    
        return back()->with('success', 'Images ajoutées avec succès.');
    }

    public function destroy(LotPhoto $image)
    {
        // Supprime l'image du stockage
        if (Storage::disk('public')->exists($image->photo_path)) {
            Storage::disk('public')->delete($image->photo_path);
        }
    
        // Supprime l'entrée dans la base de données
        $image->delete();
    
        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }
}
