<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\ParticipantRejectedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
    try {
        // Log incoming request for debug
        Log::info('Request all:', $request->all());
        Log::info('Files:', $request->file());
        Log::info('Has File recu_paie?', ['has_file' => $request->hasFile('recu_paie')]);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:participant,email',
            'fonction' => 'required|string|max:255',
            'tel' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'est_tunisien' => 'required|in:true,false,1,0',
            'participation' => 'required|string|max:255',
            'accomodation' => 'nullable|in:true,false,1,0',
            'etablissement' => 'required|string|max:255',
            'num_enfant' => 'required|integer|min:0',
            'num_adulte' => 'required|integer|min:0',
            'supp_single' => 'required|in:true,false,1,0',
            'supp_nuit' => 'required|integer|min:0',
            'prix_total' => 'required|integer|min:0',
            'edition_id' => 'required|exists:edition,id',
            'methode_paie' => 'required|string|max:255',
            'recu_paie' => 'required|file'
        ]);

        if ($request->hasFile('recu_paie')) {
            $path = $request->file('recu_paie')->store('recu_paie', 'public');
            $validated['recu_paie'] = $path;
        } else {
            return response()->json(['error' => 'No file was uploaded.'], 400);
        }
        $validated['est_tunisien'] = filter_var($validated['est_tunisien'], FILTER_VALIDATE_BOOLEAN);
        $validated['supp_single'] = filter_var($validated['supp_single'], FILTER_VALIDATE_BOOLEAN);
        $validated['accomodation'] = filter_var($validated['accomodation'], FILTER_VALIDATE_BOOLEAN);
        $participant = Participant::create($validated);

        return response()->json($participant, 201);

    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json(['errors' => $ve->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error creating participant: ' . $e->getMessage()], 500);
    }
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
                'email' => 'sometimes|email',
                'fonction' => 'sometimes|string|max:255',
                'tel' => 'sometimes|string|max:255',
                'pays' => 'sometimes|string|max:255',
                'est_tunisien' => 'sometimes|in:true,false,1,0',
                'participation' => 'sometimes|string|max:255',
                'accomodation' => 'sometimes|in:true,false,1,0',
                'etablissement' => 'sometimes|string|max:255',
                'num_enfant' => 'sometimes|integer|min:0',
                'num_adulte' => 'sometimes|integer|min:0',
                'supp_single' => 'sometimes|in:true,false,1,0',
                'supp_nuit' => 'sometimes|integer|min:0',
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

                $path = $request->file('recu_paie')->store('recu_paie', 'public');
                $validated['recu_paie'] = $path;
            }
            if (isset($validated['est_tunisien'])) {
                $validated['est_tunisien'] = filter_var($validated['est_tunisien'], FILTER_VALIDATE_BOOLEAN);
            }
            if (isset($validated['supp_single'])) {
                $validated['supp_single'] = filter_var($validated['supp_single'], FILTER_VALIDATE_BOOLEAN);
            }
            if (isset($validated['accomodation'])) {
                $validated['accomodation'] = filter_var($validated['accomodation'], FILTER_VALIDATE_BOOLEAN);
            }

            $participant->update($validated);

            return response()->json($participant);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Participant not found or invalid data' . $e->getMessage()], 404);
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
        $participantName = $participant->prenom . ' ' . $participant->nom;
        try {
            Mail::to($participant->email)->send(new ParticipantRejectedMail($participantName));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
        // Delete associated recu_paie
        if ($participant->recu_paie) {
            $path = str_replace('/storage', 'public', $participant->recu_paie);
            Storage::delete($path);
        }

        $participant->delete();

        return response()->json(null, 204);
    }
    /**
     * Display participants by edition ID.
     */
    public function getByEdition($editionId)
    {
        $participants = Participant::with('edition')
            ->where('edition_id', $editionId)
            ->get();
        return response()->json($participants);
    }
}
