<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\LotPhoto;
use Illuminate\Http\Request;

class LotImageController extends Controller
{
    public function manage(Lot $lot)
    {
        $photos = $lot->photos;
        return view('lots.images.manage', compact('lot', 'photos'));
    }

    public function store(Request $request, Lot $lot)
    {
        $request->validate([
            'photos.*' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('lot_photos', 'public');
                $lot->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('lots.images.manage', $lot->id)->with('success', 'Images ajoutées avec succès.');
    }

    public function destroy(LotPhoto $image)
    {
        $image->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }
}
