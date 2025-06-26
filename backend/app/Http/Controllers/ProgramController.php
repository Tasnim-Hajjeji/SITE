<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the programs.
     */
    public function index()
    {
        $programs = Program::all();
        return response()->json($programs);
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name_fr' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'description_fr' => 'sometimes|string',
                'description_en' => 'sometimes|string',
                'time_start' => 'required|date_format:Y-m-d H:i',
                'time_end' => 'required|date_format:Y-m-d H:i',
                'edition_id' => 'required|exists:edition,id',
            ]);


            $program = Program::create($validated);

            return response()->json($program, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error while creating program: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified program.
     */
    public function show(string $id)
    {
        $program = Program::with(['edition', 'intervenants'])->findOrFail($id);
        return response()->json($program);
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $program = Program::findOrFail($id);

            $validated = $request->validate([
                'name_fr' => 'sometimes|string|max:255',
                'name_en' => 'sometimes|string|max:255',
                'description_fr' => 'sometimes|string',
                'description_en' => 'sometimes|string',
                'time_start' => 'sometimes|date_format:Y-m-d H:i',
                'time_end' => 'sometimes|date_format:Y-m-d H:i',
                'edition_id' => 'sometimes|exists:edition,id',
                // intervenant_ids is optional and can be added later
                'intervenant_ids' => 'nullable|array',
                'intervenant_ids.*' => 'exists:intervenant,id',
            ]);

            // Remove intervenant_ids from validated data before updating the program
            $programData = collect($validated)->except('intervenant_ids')->toArray();
            $program->update($programData);

            // Sync intervenant if provided
            if ($request->has('intervenant_ids')) {
                if (empty($request->intervenant_ids)) {
                    // If empty array is sent, detach all intervenants
                    $program->intervenant()->detach();
                } else {
                    $program->intervenant()->sync($request->intervenant_ids);
                }
            }

            return response()->json($program->load(['edition', 'intervenants']));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error while updating program: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return response()->json(null, 204);
    }

    /**
     * Get programs by edition ID.
     */
    public function getProgramsByEdition($editionId)
    {
        $programs = Program::with('intervenants')
            ->where('edition_id', $editionId)
            ->orderBy('time_start', 'asc')
            ->get();

        return response()->json($programs);
    }

    /**
     * Get programs by specific date.
     */
    public function getProgramsByDate(Request $request)
    {
        try {

            $validated = $request->validate([
                'date' => 'required|date',
            ]);

            $programs = Program::with('intervenants')
                ->whereDate('time_start', $validated['date'])
                ->orderBy('time_start', 'asc')
                ->get();

            return response()->json($programs);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error while fetching ' . $e->getMessage()], 500);
        }
    }

    public function getProgramDates($editionId)
    {

        $dates = Program::select(DB::raw('DATE(time_start) as date'))
            ->distinct()
            ->where('edition_id', $editionId)
            ->orderBy('date', 'asc')
            ->pluck('date');

        return response()->json($dates);
    }
}
