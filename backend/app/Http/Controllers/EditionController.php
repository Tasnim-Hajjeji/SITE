<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EditionController extends Controller
{
    /**
     * Display a listing of the editions.
     */
    public function index()
    {
        $editions = Edition::all();
        return response()->json($editions);
    }

    /**
     * Store a newly created edition in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'place' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dossier_sponso' => 'required|file|mimes:pdf|max:10240',
            'callForPaper' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Traitement du dossier sponsor
        $dossierPath = $request->file('dossier_sponso')->store('dossiers_sponso', 'public');
        $call_for_paper = $request->file('callForPaper')->store('call_for_paper', 'public');

        // Traitement des images
        $imagesUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('editions', 'public');
                $imagesUrls[] = Storage::url($path);
            }
        }

        $edition = Edition::create([
            'name' => $request->name,
            'description_fr' => $request->description_fr,
            'description_en' => $request->description_en,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'place' => $request->place,
            'dossier_sponso' => $dossierPath,
            'images_url' => $imagesUrls,
            'call_for_paper' => $call_for_paper,
        ]);

        return response()->json($edition, 201);
    }

    /**
     * Display the specified edition.
     */
    public function show(string $id)
    {
        $edition = Edition::findOrFail($id);
        return response()->json($edition);
    }

    /**
     * Update the specified edition in storage.
     */
    public function update(Request $request, string $id)
    {
        $edition = Edition::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description_fr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'place' => 'sometimes|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dossier_sponso' => 'sometimes|file|mimes:pdf|max:10240',
            'callForPaper' => 'sometimes|file|mimes:pdf|max:10240',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only([
            'name',
            'description_fr',
            'description_en',
            'start_date',
            'end_date',
            'place'
        ]);

        // Gestion du dossier sponsor
        if ($request->hasFile('dossier_sponso')) {
            // Suppression de l'ancien fichier
            if ($edition->dossier_sponso) {
                Storage::disk('public')->delete($edition->dossier_sponso);
            }
            $data['dossier_sponso'] = $request->file('dossier_sponso')->store('dossiers_sponso', 'public');
        }
        if ($request->hasFile('callForPaper')) {
            // Suppression de l'ancien fichier
            if ($edition->call_for_paper) {
                Storage::disk('public')->delete($edition->call_for_paper);
            }
            $data['call_for_paper'] = $request->file('callForPaper')->store('call_for_paper', 'public');
        }
        // Gestion des images
        if ($request->hasFile('images')) {
            // Suppression des anciennes images
            if ($edition->images_url) {
                foreach ($edition->images_url as $url) {
                    $path = str_replace('/storage/', '', $url);
                    Storage::disk('public')->delete($path);
                }
            }

            $imagesUrls = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('editions', 'public');
                $imagesUrls[] = Storage::url($path);
            }
            $data['images_url'] = $imagesUrls;
        }

        $edition->update($data);

        return response()->json($edition);
    }

    /**
     * Remove the specified edition from storage.
     */
    public function destroy(string $id)
    {
        $edition = Edition::findOrFail($id);

        // Suppression du dossier sponsor
        if ($edition->dossier_sponso) {
            Storage::disk('public')->delete($edition->dossier_sponso);
        }

        // Suppression des images
        if ($edition->images_url) {
            foreach ($edition->images_url as $url) {
                $path = str_replace('/storage/', '', $url);
                Storage::disk('public')->delete($path);
            }
        }

        $edition->delete();

        return response()->json(null, 204);
    }

    /**
     * Add images to an existing edition.
     */
    public function addImages(Request $request, string $id)
    {
        $edition = Edition::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentImages = $edition->images_url ?: [];
        $newImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('editions', 'public');
            $newImages[] = $path;
        }

        $edition->update([
            'images_url' => array_merge($currentImages, $newImages)
        ]);

        return response()->json($edition);
    }

    /**
     * Remove a specific image from an edition.
     */
    public function removeImage(string $id, string $imageIndex)
    {
        $edition = Edition::findOrFail($id);

        if (!$edition->images_url || !isset($edition->images_url[$imageIndex])) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $images = $edition->images_url;
        $imageToRemove = $images[$imageIndex];

        // Suppression du fichier image
        $path = str_replace('/storage/', '', $imageToRemove);
        Storage::disk('public')->delete($path);

        // Suppression de l'URL du tableau
        array_splice($images, $imageIndex, 1);

        $edition->update(['images_url' => $images]);

        return response()->json($edition);
    }

    /**
     * Get the current edition (eldest where start_date > current date).
     */
    public function getCurrentEdition()
    {
        $currentEdition = Edition::where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->first();

        // If no upcoming edition exists, get the most recent past edition
        if (!$currentEdition) {
            $currentEdition = Edition::where('end_date', '<=', now())
                ->orderBy('end_date', 'desc')
                ->first();
        }

        // If still no edition found, get the most recent edition regardless of dates
        if (!$currentEdition) {
            $currentEdition = Edition::orderBy('created_at', 'desc')->first();
        }

        return response()->json($currentEdition);
    }

    /**
     * Get previous editions (where end_date < current date).
     */
    public function getPreviousEditions()
    {
        $previousEditions = Edition::where('end_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($previousEditions);
    }

    /**
     * Delete edition by ID.
     */
    public function deleteById($id)
    {
        $edition = Edition::findOrFail($id);

        // Suppression du dossier sponsor
        if ($edition->dossier_sponso) {
            Storage::disk('public')->delete($edition->dossier_sponso);
        }

        // Suppression des images
        if ($edition->images_url) {
            foreach ($edition->images_url as $url) {
                $path = str_replace('/storage/', '', $url);
                Storage::disk('public')->delete($path);
            }
        }

        $edition->delete();

        return response()->json(['message' => 'Edition deleted successfully'], 200);
    }
}
