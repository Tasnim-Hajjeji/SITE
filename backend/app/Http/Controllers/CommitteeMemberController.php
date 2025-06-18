<?php

namespace App\Http\Controllers;

use App\Models\CommitteeMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommitteeMemberController extends Controller
{
    /**
     * Display a listing of the committee members.
     */
    public function index()
    {
        $members = CommitteeMember::with('edition')->get();
        return response()->json($members);
    }

    /**
     * Store a newly created committee member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'committee' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'from_etablissement' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'edition_id' => 'required|exists:edition,id',
        ]);

        // Remove the image file object from validated data
        unset($validated['image']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/committee_members');
            $validated['image_url'] = Storage::url($path);
        }

        $member = CommitteeMember::create($validated);

        return response()->json($member, 201);
    }

    /**
     * Display the specified committee member.
     */
    public function show(string $id)
    {
        $member = CommitteeMember::with('edition')->findOrFail($id);
        return response()->json($member);
    }

    /**
     * Update the specified committee member in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = CommitteeMember::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'committee' => 'sometimes|string|max:255',
            'role' => 'sometimes|string|max:255',
            'from_etablisement' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'edition_id' => 'sometimes|exists:edition,id',
        ]);

        // Remove the image file object from validated data if present
        if (isset($validated['image'])) {
            unset($validated['image']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($member->image_url) {
                $oldPath = str_replace('/storage', 'public', $member->image_url);
                Storage::delete($oldPath);
            }

            $path = $request->file('image')->store('public/committee_members');
            $validated['image_url'] = Storage::url($path);
        }

        $member->update($validated);

        // Return the updated member with fresh data
        return response()->json($member->fresh());
    }

    /**
     * Remove the specified committee member from storage.
     */
    public function destroy(string $id)
    {
        $member = CommitteeMember::findOrFail($id);

        // Delete associated image
        if ($member->image_url) {
            $path = str_replace('/storage', 'public', $member->image_url);
            Storage::delete($path);
        }

        $member->delete();

        return response()->json(null, 204);
    }

    /**
     * Get members by edition and committee.
     */
    public function getMembersByEditionAndCommittee($editionId, $committee)
    {
        $members = CommitteeMember::where('edition_id', $editionId)
            ->where('committee', $committee)
            ->orderBy('role', 'asc')
            ->get();

        return response()->json($members);
    }
}