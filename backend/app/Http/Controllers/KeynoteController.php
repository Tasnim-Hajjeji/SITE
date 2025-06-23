<?php

namespace App\Http\Controllers;

use App\Models\Keynote;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class KeynoteController extends Controller
{
    // GET /keynotes - List all keynotes
    public function index()
    {
        $keynotes = Keynote::with(['edition'])->get();
        return response()->json($keynotes);
    }

    // GET /keynotes/{id} - Show a specific keynote
    public function show($id)
    {
        $keynote = Keynote::with(['edition'])->findOrFail($id);
        return response()->json($keynote);
    }

    // POST /keynotes - Create a new keynote
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'description_fr' => 'required|string',
                'description_en' => 'required|string',
                'speaker_name' => 'required|string',
                'speaker_role' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
                'edition_id' => 'required|exists:edition,id',
            ]);
            unset($validated['image']);
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('keynotes', 'public');
                $validated['image_url'] = $path;
            }
            $keynote = Keynote::create($validated);
            return response()->json([
                'message' => 'Keynote created successfully.',
                'keynote' => $keynote->load(['edition']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating keynote: ' . $e->getMessage()], 500);
        }
    }

    // PUT/PATCH /keynotes/{id} - Update a keynote
    public function update(Request $request, $id)
    {
        $keynote = Keynote::findOrFail($id);

        $validated = $request->validate([
            'description_fr' => 'sometimes|string',
            'description_en' => 'sometimes|string',
            'speaker_name' => 'sometimes|string',
            'speaker_role' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:8192',
            'edition_id' => 'sometimes|exists:edition,id',
        ]);

        // Remove image from validated array to handle separately
        unset($validated['image']);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($keynote->image_url) {
                // If image_url is just the path (e.g., "keynotes/filename.jpg")
                Storage::disk('public')->delete($keynote->image_url);
            }

            // Store new image
            $path = $request->file('image')->store('keynotes', 'public');
            $validated['image_url'] = $path;
        }

        $keynote->update($validated);

        return response()->json([
            'message' => 'Keynote updated successfully.',
            'keynote' => $keynote->load(['edition']),
        ]);
    }
    // DELETE /keynotes/{id} - Delete a keynote
    public function destroy($id)
    {
        $keynote = Keynote::findOrFail($id);
        $keynote->delete();

        return response()->json([
            'message' => 'Keynote deleted successfully.',
        ]);
    }

    // GET /keynotes/edition/{editionId} - Get keynotes by edition
    public function getByEdition($editionId)
    {
        $keynotes = Keynote::where('edition_id', $editionId)
            ->with(['edition'])
            ->get();

        return response()->json($keynotes);
    }
}
