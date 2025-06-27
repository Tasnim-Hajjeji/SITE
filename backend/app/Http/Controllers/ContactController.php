<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts=Contact::all();
        return response()->json($contacts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255',
            'subject'=>'required|string',
            'message'=>'required|string',

        ]);
        $contact=Contact::create($validated);
        return response()->json($contact,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact=Contact::findOrFail($id);
        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contact=Contact::findOrFail($id);
        $validated=$request->validate([
            'name'=>'sometimes|string|max:255',
            'email'=>'sometimes|email|max:255',
            'subject'=>'sometimes|string',
            'message'=>'sometimes|string',

        ]);
        $contact->update($validated);
        return response()->json($contact);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact=Contact::findOrFail($id);
        $contact->delete();
        return response()->json(null,204);
    }
}
