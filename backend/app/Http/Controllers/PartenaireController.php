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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
            'edition_id' => 'required|exists:edition,id',
        ]);
        unset($validated['image']); // Remove image from validation array
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
    public function update(Request $request, string $id)
    {
        try {
            $partenaire = Partenaire::findOrFail($id);
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:8192',
                'edition_id' => 'sometimes|exists:edition,id',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($partenaire->image_url) {
                    // Parse URL path part, e.g. "/storage/intervenants/filename.jpg"
                    $urlPath = parse_url($partenaire->image_url, PHP_URL_PATH);

                    // Remove "/storage/" prefix from the path to get relative storage path
                    $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');

                    // Delete from 'public' disk (storage/app/public/)
                    Storage::disk('public')->delete($relativePath);
                }

                $path = $request->file('image')->store('partenaires');
                $validated['image_url'] = Storage::url($path);
            }

            $partenaire->update($validated);
            return response()->json($partenaire);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Partenaire'.$e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified partner.
     */
    public function destroy(string $id)
    {
        $partenaire = Partenaire::findOrFail($id);

        if (!$partenaire) {
            return response()->json(['error' => 'Partenaire not found'], 404);
        }
    
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
