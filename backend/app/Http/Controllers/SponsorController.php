<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    /**
     * Display a listing of the sponsors.
     */
    public function index()
    {
        $sponsors = Sponsor::all();
        return response()->json($sponsors);
    }

    /**
     * Store a newly created sponsor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string',
            'logo' => 'required|image', // Assuming logo is an image file
            'edition_id' => 'required|exists:edition,id',
        ]);
        unset($validated['logo']); // Remove logo from validation array
        if($request->hasFile('logo')) {
            $path = $request->file('logo')->store('sponsors');
            $validated['logo'] = Storage::url($path); // Store the path of the uploaded logo
        } else {
            return response()->json(['error' => 'Logo is required'], 422);
        }
        
        $sponsor = Sponsor::create($validated);
        return response()->json($sponsor, 201);
    }

    /**
     * Display the specified sponsor.
     */
    public function show(string $id)
    {
        $sponsor = Sponsor::with('edition')->findOrFail($id);
        return response()->json($sponsor);
    }

    /**
     * Update the specified sponsor in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
        $sponsor = Sponsor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'description' => 'sometimes|string',
            'logo' => 'sometimes|image', // Assuming logo is an image file
            'edition_id' => 'sometimes|exists:edition,id',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old image
            if ($sponsor->logo) {
                // Parse URL path part, e.g. "/storage/intervenants/filename.jpg"
                $urlPath = parse_url($sponsor->logo, PHP_URL_PATH);

                    // Remove "/storage/" prefix from the path to get relative storage path
                    $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');

                // Delete from 'public' disk (storage/app/public/)
                Storage::disk('public')->delete($relativePath);
            }
            unset($validated['logo']); // Remove logo from validation array
            $path = $request->file('logo')->store('sponsors');
            $validated['logo'] = Storage::url($path);
        }

        $sponsor->update($validated);

        return response()->json($sponsor);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update sponsor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified sponsor from storage.
     */
    public function destroy(string $id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->delete();

        return response()->json(null, 204);
    }

    public function getSponsorsByEdition($editionId)
    {
        $sponsors = Sponsor::where('edition_id', $editionId)->get();
        return response()->json($sponsors);
    }
}