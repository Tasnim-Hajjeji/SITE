<?php

namespace App\Http\Controllers;

use App\Models\Intervenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class IntervenantController extends Controller
{
    /**
     * Display a listing of the intervenants.
     */
    public function index()
    {
        $intervenants = Intervenant::with(['edition', 'programs'])->get();
        return response()->json($intervenants);
    }

    /**
     * Store a newly created intervenant in storage.
     */

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'description_fr' => 'sometimes|string|nullable',
                'description_en' => 'sometimes|string|nullable',
                'profession_fr' => 'required|string',
                'profession_en' => 'required|string',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
                'edition_id' => 'required|exists:edition,id',
                'program_ids' => 'sometimes|array',
                'program_ids.*' => 'exists:program,id',
                'institut' => 'required|string|max:255',
                'code_pays' => 'required|string|max:10'
            ]);

            $programIds = $validated['program_ids'] ?? [];
            unset($validated['photo'], $validated['program_ids']);

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('intervenants', 'public');
                $validated['image_url'] = $path;
            }

            $intervenant = Intervenant::create($validated);

            if (!empty($programIds)) {
                $intervenant->programs()->sync($programIds);
            }

            return response()->json($intervenant, 201);
        } catch (\Exception $e) {
            Log::error('Error creating intervenant: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating intervenant:' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified intervenant.
     */
    public function show(string $id)
    {
        $intervenant = Intervenant::with(['edition', 'programs'])->find($id);

        if (!$intervenant) {
            return response()->json(['error' => 'Intervenant not found'], 404);
        }

        return response()->json($intervenant);
    }


    /**
     * Update the specified intervenant in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $intervenant = Intervenant::findOrFail($id);

            $validated = $request->validate([
                'full_name' => 'sometimes|string|max:255',
                'description_fr' => 'sometimes|string|nullable',
                'description_en' => 'sometimes|string|nullable',
                'profession_fr' => 'sometimes|string',
                'profession_en' => 'sometimes|string',
                'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:8192',
                'edition_id' => 'sometimes|exists:edition,id',
                'program_ids' => 'sometimes|array',
                'program_ids.*' => 'exists:program,id',
                'institut' => 'sometimes|string|max:255',
                'code_pays' => 'sometimes|string|max:10'
            ]);

            unset($validated['photo'], $validated['program_ids']);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                if ($intervenant->image_url) {
                    // Parse URL path part, e.g. "/storage/intervenants/filename.jpg"
                    $urlPath = parse_url($intervenant->image_url, PHP_URL_PATH);

                    // Remove "/storage/" prefix from the path to get relative storage path
                    $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');

                    // Delete from 'public' disk (storage/app/public/)
                    Storage::disk('public')->delete($relativePath);
                }


                $path = $request->file('photo')->store('intervenants', 'public');
                $validated['image_url'] = $path;
            }

            $intervenant->update($validated);

            // Sync programs if provided
            if ($request->has('program_ids')) {
                $intervenant->programs()->sync($request->program_ids);
            }

            return response()->json($intervenant->load('programs'));
        } catch (\Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            return response()->json(['error' => 'Update error: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified intervenant from storage.
     */
    public function destroy(string $id)
    {
        $intervenant = Intervenant::findOrFail($id);

        // Delete associated photo
        if ($intervenant->image_url) {
            $path = str_replace('/storage', 'public', $intervenant->image_url);
            Storage::delete($path);
        }

        $intervenant->delete();

        return response()->json(null, 204);
    }
    /**
     * Get intervenants by edition ID.
     */
    public function getByEdition($editionId)
    {
        $intervenants = Intervenant::with('programs')
            ->where('edition_id', $editionId)
            ->get();

        return response()->json($intervenants);
    }
    public function addPrograms(Request $request, $id)
    {
        $request->validate([
            'program_ids' => 'required|array',
            'program_ids.*' => 'exists:program,id',
        ]);

        $intervenant = Intervenant::findOrFail($id);

        // Add new programs without removing existing ones
        $intervenant->programs()->syncWithoutDetaching($request->program_ids);

        return response()->json([
            'message' => 'Programs added successfully.',
            'intervenant' => $intervenant->load('programs'),
        ]);
    }
}
