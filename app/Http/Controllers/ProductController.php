<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Container;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.manage', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'container_id' => 'required|exists:containers,id',
            'price' => 'required|numeric|min:0',
            'raw_material_cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'is_sterilized' => 'boolean',
        ]);
        //dd($request);
        Product::create($validated);
        return redirect()->route('products.manage')->with('success', 'Produit ajouté avec succès.');
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
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'container_id' => 'required|exists:containers,id',
            'price' => 'required|numeric|min:0',
            'raw_material_cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_sterilized' => 'boolean',
        ]);
    
        // Mise à jour du produit
        $product->update($request->only(['name', 'container_id', 'price', 'raw_material_cost', 'stock', 'description', 'is_sterilized']));
        //dd($request->all());

        return redirect()->route('products.manage')->with('success', 'Produit mis à jour avec succès.');
    }

    public function publicIndex(Request $request)
    {
        $query = Product::where('stock', '>', 0);
    
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%");
            });
        }
        
        
        $products = $query->orderBy('name', 'asc')->paginate(10);
    
        return view('products.public', compact('products'));
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
    
        return redirect()->route('products.manage')->with('success', 'Produit supprimé avec succès.');
    }

    public function manage(Request $request)
    {
        $search = $request->input('search');
    
        $productsQuery = Product::query();
    
        if ($search) {
            $productsQuery->where('name', 'like', "%$search%")
                          ->orWhere('description', 'like', "%$search%")
                          ->orWhereHas('container', function ($query) use ($search) {
                              $query->where('size', 'like', "%$search%");
                          });
        }
    
        $products = $productsQuery->orderBy('name', 'asc')->paginate(10);
        $containers = Container::all();
        return view('products.manage', [
            'products' => $products,
            'search' => $search,
            'containers' => $containers,
        ]);
    }
    
    
    

}
