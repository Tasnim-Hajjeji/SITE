<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the participants.
     */
    public function index()
    {
        $participants = Participant::with('edition')->get();
        return response()->json($participants);
    }

    /**
     * Store a newly created participant in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:participant,email',
            'fonction' => 'required|string|max:255',
            'tel' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'etablissement' => 'required|string|max:255',
            'num_enfant' => 'required|integer|min:0',
            'num_adulte' => 'required|integer|min:0',
            'supp_single' => 'required|boolean',
            'supp_nuit' => 'required|boolean',
            'edition_id' => 'required|exists:edition,id',
        ]);

        $participant = Participant::create($validated);

        return response()->json($participant, 201);
    }

    /**
     * Display the specified participant.
     */
    public function show(string $id)
    {
        $participant = Participant::with('edition')->findOrFail($id);
        return response()->json($participant);
    }

    /**
     * Update the specified participant in storage.
     */
    public function update(Request $request, string $id)
    {
        $participant = Participant::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:participant,email,'.$id,
            'fonction' => 'sometimes|string|max:255',
            'tel' => 'sometimes|string|max:255',
            'pays' => 'sometimes|string|max:255',
            'etablissement' => 'sometimes|string|max:255',
            'num_enfant' => 'sometimes|integer|min:0',
            'num_adulte' => 'sometimes|integer|min:0',
            'supp_single' => 'sometimes|boolean',
            'supp_nuit' => 'sometimes|boolean',
            'edition_id' => 'sometimes|exists:edition,id',
        ]);

        $participant->update($validated);

        return response()->json($participant);
    }

    /**
     * Remove the specified participant from storage.
     */
    public function destroy(string $id)
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();

        return response()->json(null, 204);
    }
}