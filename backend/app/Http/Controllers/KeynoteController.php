<?php

namespace App\Http\Controllers;

use App\Models\Keynote;
use App\Models\Speaker;
use App\Models\CommitteeMember;
use App\Models\Intervenant;
use Illuminate\Http\Request;

class KeynoteController extends Controller
{
    // GET /keynotes - List all keynotes
    public function index()
    {
        $keynotes = Keynote::with(['speakerable', 'edition'])->get();
        return response()->json($keynotes);
    }

    // GET /keynotes/{id} - Show a specific keynote
    public function show($id)
    {
        $keynote = Keynote::with(['speakerable', 'edition'])->findOrFail($id);
        return response()->json($keynote);
    }

    // POST /keynotes - Create a new keynote
    public function store(Request $request)
    {
        try{

            $validated = $request->validate([
                'description_fr' => 'required|string',
                'description_en' => 'required|string',
                'type' => 'required|in:speaker,committee',
                'speaker_id' => 'required|integer',
                'edition_id' => 'required|exists:edition,id',
            ]);
    
            if ($request->type === 'speaker') {
                $speaker = Intervenant::findOrFail($request->speaker_id);
                $keynote = $speaker->keynotes()->create([
                    'description_fr' => $request->description_fr,
                    'description_en' => $request->description_en,
                    'edition_id'     => $request->edition_id,
                ]);
            } else {
                $committee = CommitteeMember::findOrFail($request->speaker_id);
                $keynote = $committee->keynotes()->create([
                    'description_fr' => $request->description_fr,
                    'description_en' => $request->description_en,
                    'edition_id'     => $request->edition_id,
                ]);
            }
    
            return response()->json([
                'message' => 'Keynote created successfully.',
                'keynote' => $keynote->load(['speakerable', 'edition']),
            ], 201);
        }catch (\Exception $e) {
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
            'edition_id' => 'sometimes|exists:edition,id',
        ]);

        $keynote->update($validated);

        return response()->json([
            'message' => 'Keynote updated successfully.',
            'keynote' => $keynote->load(['speakerable', 'edition']),
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
        $keynotes = Keynote::with('speakerable')
            ->where('edition_id', $editionId)
            ->get();

        return response()->json($keynotes);
    }
}
