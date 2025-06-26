<?php

namespace App\Http\Controllers;

use App\Models\Intervenant;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommitteeMember;
use App\Models\Partenaire;
use App\Models\Participant;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        try{

            $request->validate([
                'query' => 'required|string|min:2',
                'edition_id' => 'required|integer'
            ]);
    
            $query = $request->input('query');
            $editionId = $request->input('edition_id');
            $query = trim($query);
    
            // Search Speakers
            $speakers = Intervenant::where('edition_id', $editionId)
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'LIKE', "%{$query}%")
                        ->orWhere('profession_fr', 'LIKE', "%{$query}%")
                        ->orWhere('profession_en', 'LIKE', "%{$query}%")
                        ->orWhere('institut', 'LIKE', "%{$query}%")
                        ->orWhere('description_fr', 'LIKE', "%{$query}%")
                        ->orWhere('description_en', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($speaker) {
                    return [
                        'id' => $speaker->id,
                        'type' => 'speaker',
                        'name' => $speaker->full_name,
                        'description' => $speaker->profession_en . '-' . $speaker->institut,
                        'url' => '/admin/speaker'
                    ];
                });
    
            // Search Sponsors
            $sponsors = Sponsor::where('edition_id', $editionId)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($sponsor) {
                    return [
                        'id' => $sponsor->id,
                        'type' => 'sponsor',
                        'name' => $sponsor->name,
                        'description' => $sponsor->description,
                        'url' => '/admin/sponsor'
                    ];
                });
            $partners = Partenaire::where('edition_id', $editionId)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($partenaire) {
                    return [
                        'id' => $partenaire->id,
                        'type' => 'partner',
                        'name' => $partenaire->name,
                        'description' => $partenaire->description,
                        'url' => '/admin/partnair'
                    ];
                });
            $participants = Participant::where('edition_id', $editionId)
                ->where(function ($q) use ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")
                        ->orWhere('prenom', 'LIKE', "%{$query}%")
                        ->orWhere('fonction', 'LIKE', "%{$query}%")
                        ->orWhere('pays', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($participant) {
                    return [
                        'id' => $participant->id,
                        'type' => 'participant',
                        'name' => $participant->nom . ' ' . $participant->prenom,
                        'description' => $participant->fonction . ' - ' . $participant->pays,
                        'url' => '/admin/participant-card'
                    ];
                });
            $committee_members = CommitteeMember::where('edition_id', $editionId)
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'LIKE', "%{$query}%")
                        ->orWhere('role', 'LIKE', "%{$query}%")
                        ->orWhere('from_etablissement', 'LIKE', "%{$query}%");
                        })
                ->limit(5)
                ->get()
                ->map(function ($committee_member) {
                    return [
                        'id' => $committee_member->id,
                        'type' => 'committee',
                        'name' => $committee_member->full_name,
                        'description' => $committee_member->role . ' - ' . $committee_member->from_etablissement,
                        'url' => '/admin/committies'
                    ];
                });
    
            return array_merge(
                $speakers->toArray(),
                $sponsors->toArray(),
                $participants->toArray(),
                $committee_members->toArray(),
                $partners->toArray()
            );
        }catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
