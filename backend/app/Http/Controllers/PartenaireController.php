<?php

namespace App\Http\Controllers;

use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    /**
     * Display a listing of partners.
     */
    public function index()
    {
        $partenaires = Partenaire::with('edition')->get();
        return response()->json($partenaires);
    }

    /**
     * Store a newly created partner.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'edition_id' => 'required|exists:edition,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('partenaires');
            $validated['image_url'] = Storage::url($path);
        }

        $partenaire = Partenaire::create($validated);
        return response()->json($partenaire, 201);
    }

    /**
     * Display the specified partner.
     */
    public function show(string $id)
    {
        $partenaire = Partenaire::with('edition')->findOrFail($id);
        return response()->json($partenaire);
    }

    /**
     * Update the specified partner.
     */
    public function update(Request $request, Partenaire $partenaire)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'edition_id' => 'sometimes|exists:edition,id',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($partenaire->image_url) {
                $oldPath = str_replace('/storage', 'public', $partenaire->image_url);
                Storage::delete($oldPath);
            }

            $path = $request->file('image')->store('partenaires');
            $validated['image_url'] = Storage::url($path);
        }

        $partenaire->update($validated);
        return response()->json($partenaire);
    }

    /**
     * Remove the specified partner.
     */
    public function destroy(Partenaire $partenaire)
    {
        // Delete associated image
        if ($partenaire->image_url) {
            $path = str_replace('/storage', 'public', $partenaire->image_url);
            Storage::delete($path);
        }

        $partenaire->delete();
        return response()->json(null, 204);
    }

    /**
     * Get partners by edition ID (Additional custom method)
     */
    public function getPartenairesByEdition($editionId)
    {
        $partenaires = Partenaire::where('edition_id', $editionId)->get();
        return response()->json($partenaires);
    }
}