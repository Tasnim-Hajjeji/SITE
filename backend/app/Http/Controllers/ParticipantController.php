<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'est_tunisien' => 'required|boolean',
            'etablissement' => 'required|string|max:255',
            'num_enfant' => 'required|integer|min:0',
            'num_adulte' => 'required|integer|min:0',
            'supp_single' => 'required|boolean',
            'supp_nuit' => 'required|boolean',
            'prix_total' => 'required|integer|min:0',
            'edition_id' => 'required|exists:edition,id',
            'methode_paie' => 'required|string|max:255',
            'recu_paie' => 'required|file',
        ]);
        unset($validated['recu_paie']); // Remove recu_paie from validation array
        if ($request->hasFile('recu_paie')) {
            $path = $request->file('recu_paie')->store('recu_paie');
            $validated['recu_paie'] = Storage::url($path);
        }
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
        try {
            $participant = Participant::findOrFail($id);
            $validated = $request->validate([
                'nom' => 'sometimes|string|max:255',
                'prenom' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:participant,email,' . $id,
                'fonction' => 'sometimes|string|max:255',
                'tel' => 'sometimes|string|max:255',
                'pays' => 'sometimes|string|max:255',
                'est_tunisien' => 'sometimes|boolean',
                'etablissement' => 'sometimes|string|max:255',
                'num_enfant' => 'sometimes|integer|min:0',
                'num_adulte' => 'sometimes|integer|min:0',
                'supp_single' => 'sometimes|boolean',
                'supp_nuit' => 'sometimes|boolean',
                'edition_id' => 'sometimes|exists:edition,id',
                'methode_paie' => 'sometimes|string|max:255',
                'recu_paie' => 'sometimes|file',
            ]);
            unset($validated['recu_paie']); // Remove recu_paie from validation array
            // Handle image upload
            if ($request->hasFile('recu_paie')) {
                // Delete old image
                if ($participant->recu_paie) {
                    // Parse URL path part, e.g. "/storage/intervenants/filename.jpg"
                    $urlPath = parse_url($participant->recu_paie, PHP_URL_PATH);

                    // Remove "/storage/" prefix from the path to get relative storage path
                    $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');

                    // Delete from 'public' disk (storage/app/public/)
                    Storage::disk('public')->delete($relativePath);
                }

                $path = $request->file('recu_paie')->store('recu_paie');
                $validated['recu_paie'] = Storage::url($path);
            }

            $participant->update($validated);

            return response()->json($participant);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Participant not found or invalid data'], 404);
        }
    }

    /**
     * Remove the specified participant from storage.
     */
    public function destroy(string $id)
    {
        $participant = Participant::findOrFail($id);
        if (!$participant) {
            return response()->json(['error' => 'Participant not found'], 404);
        }

        // Delete associated recu_paie
        if ($participant->recu_paie) {
            $path = str_replace('/storage', 'public', $participant->recu_paie);
            Storage::delete($path);
        }

        $participant->delete();

        return response()->json(null, 204);
    }
}
