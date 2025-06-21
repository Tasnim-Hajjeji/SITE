<?php

namespace App\Http\Controllers;

use App\Models\ImportantDate;
use Illuminate\Http\Request;

use function Pest\Laravel\call;

class ImportantDateController extends Controller
{
    /**
     * Display a listing of the important dates.
     */
    public function index()
    {
        $dates = ImportantDate::with('edition')->get();
        return response()->json($dates);
    }

    /**
     * Store a newly created important date in storage.
     */
    public function store(Request $request)
    {
        try{

            $validated = $request->validate([
                'title_fr' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description_fr' => 'sometimes|string',
                'description_en' => 'sometimes|string',
                'date' => 'required|date',
                'edition_id' => 'required|exists:edition,id',
            ]);
    
            $date = ImportantDate::create($validated);
    
            return response()->json($date, 201);
        }catch(\Exception $e){
            return response()->json(['error' => 'An error occurred while creating the important date.'.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified important date.
     */
    public function show(string $id)
    {
        $date = ImportantDate::with('edition')->findOrFail($id);
        return response()->json($date);
    }

    /**
     * Update the specified important date in storage.
     */
    public function update(Request $request, string $id)
    {
        $date = ImportantDate::findOrFail($id);

        $validated = $request->validate([
            'title_fr' => 'sometimes|string|max:255',
            'title_en' => 'sometimes|string|max:255',
            'description_fr' => 'sometimes|string',
            'description_en' => 'sometimes|string',
            'date' => 'sometimes|date',
            'edition_id' => 'sometimes|exists:edition,id',
        ]);

        $date->update($validated);

        return response()->json($date);
    }

    /**
     * Remove the specified important date from storage.
     */
    public function destroy(string $id)
    {
        $date = ImportantDate::findOrFail($id);
        $date->delete();

        return response()->json(null, 204);
    }
    /**
     * Get important dates by edition ID.
     */
    public function getDatesByEdition($editionId)
    {
        $dates = ImportantDate::where('edition_id', $editionId)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($dates);
    }

    public function getLatestCountdownDate($editionId)
    {
        $latestDate = ImportantDate::where('edition_id', $editionId)
            ->where('date', '>', now()->toDateString())
            ->orderBy('date', 'asc')
            ->first();

        if ($latestDate) {
            return response()->json($latestDate);
        } else {
            return response()->json(['message' => 'No upcoming important dates found'], 404);
        }
    }
}