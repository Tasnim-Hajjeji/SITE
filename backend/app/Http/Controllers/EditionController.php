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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'place' => 'required|string|max:255',
            'tun_price' => 'required|integer|min:0',
            'eur_price' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $editionData = $validator->validated();
        $editionData['images_url'] = [];

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/editions');
                $imageUrls[] = Storage::url($path);
            }
            $editionData['images_url'] = $imageUrls;
        }

        $edition = Edition::create($editionData);

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
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'place' => 'sometimes|string|max:255',
            'tun_price' => 'sometimes|integer|min:0',
            'eur_price' => 'sometimes|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $editionData = $validator->validated();

        if ($request->hasFile('images')) {
            if ($edition->images_url) {
                foreach ($edition->images_url as $url) {
                    $path = str_replace('/storage', 'public', $url);
                    Storage::delete($path);
                }
            }

            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/editions');
                $imageUrls[] = Storage::url($path);
            }
            $editionData['images_url'] = $imageUrls;
        } elseif (isset($editionData['images'])) {
            // If images array is provided but empty (to clear all images)
            if (empty($editionData['images'])) {
                // Delete old images if they exist
                if ($edition->images_url) {
                    foreach ($edition->images_url as $url) {
                        $path = str_replace('/storage', 'public', $url);
                        Storage::delete($path);
                    }
                }
                $editionData['images_url'] = [];
            }
        }

        $edition->update($editionData);

        return response()->json($edition);
    }

    /**
     * Remove the specified edition from storage.
     */
    public function destroy(string $id)
    {
        $edition = Edition::findOrFail($id);

        // Delete associated images if they exist
        if ($edition->images_url) {
            foreach ($edition->images_url as $url) {
                $path = str_replace('/storage', 'public', $url);
                Storage::delete($path);
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentImages = $edition->images_url ?: [];
        $newImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('public/editions');
            $newImages[] = Storage::url($path);
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

        // Delete the image file
        $path = str_replace('/storage', 'public', $imageToRemove);
        Storage::delete($path);

        // Remove the image from the array
        array_splice($images, $imageIndex, 1);

        $edition->update(['images_url' => $images]);

        return response()->json($edition);
    }
}