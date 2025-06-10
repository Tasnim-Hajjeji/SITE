<?php

namespace App\Http\Controllers;

use App\Models\Intervenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'edition_id' => 'required|exists:edition,id',
            'program_ids' => 'sometimes|array',
            'program_ids.*' => 'exists:program,id',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/intervenants');
            $validated['photo_url'] = Storage::url($path);
        }

        $intervenant = Intervenant::create($validated);

        // Sync programs if provided
        if ($request->has('program_ids')) {
            $intervenant->programs()->sync($request->program_ids);
        }

        return response()->json($intervenant->load('programs'), 201);
    }

    /**
     * Display the specified intervenant.
     */
    public function show(string $id)
    {
        $intervenant = Intervenant::with(['edition', 'programs'])->findOrFail($id);
        return response()->json($intervenant);
    }

    /**
     * Update the specified intervenant in storage.
     */
    public function update(Request $request, string $id)
    {
        $intervenant = Intervenant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'edition_id' => 'sometimes|exists:edition,id',
            'program_ids' => 'sometimes|array',
            'program_ids.*' => 'exists:program,id',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($intervenant->photo_url) {
                $oldPath = str_replace('/storage', 'public', $intervenant->photo_url);
                Storage::delete($oldPath);
            }

            $path = $request->file('photo')->store('public/intervenants');
            $validated['photo_url'] = Storage::url($path);
        }

        $intervenant->update($validated);

        // Sync programs if provided
        if ($request->has('program_ids')) {
            $intervenant->programs()->sync($request->program_ids);
        }

        return response()->json($intervenant->load('programs'));
    }

    /**
     * Remove the specified intervenant from storage.
     */
    public function destroy(string $id)
    {
        $intervenant = Intervenant::findOrFail($id);

        // Delete associated photo
        if ($intervenant->photo_url) {
            $path = str_replace('/storage', 'public', $intervenant->photo_url);
            Storage::delete($path);
        }

        $intervenant->delete();

        return response()->json(null, 204);
    }
}