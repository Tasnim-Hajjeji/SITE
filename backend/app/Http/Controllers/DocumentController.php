<?php

namespace App\Http\Controllers;

use App\Models\document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index()
    {
        $documents = Document::with('edition')->get();
        return response()->json($documents);
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:5120',
            'edition_id' => 'nullable|exists:edition,id',
        ]);

        unset($validated['file']);
        $validated['url'] = null;
        // Handle file upload
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('documents','public');
            $validated['url'] = $path;
        }

        $document = Document::create($validated);

        return response()->json($document, 201);
    }

    /**
     * Display the specified document.
     */
    public function show(string $id)
    {
        $document = Document::with('edition')->findOrFail($id);
        return response()->json($document);
    }

    /**
     * Download the specified document.
     */
    public function download(string $id)
    {
        $document = Document::findOrFail($id);

        $path = str_replace('http://localhost/storage/', '', $document->url);
        if (!Storage::exists($path)) {
            dd("File does not exist at path: " . $path);
            abort(404);
        }
        return Storage::download($path, $document->name);
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, string $id)
    {
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'name_fr' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'description_fr' => 'sometimes|string',
            'description_en' => 'sometimes|string',
            'file' => 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:5120',
            'edition_id' => 'nullable|exists:edition,id',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($document->url) {
                $oldPath = str_replace('/storage', 'public', $document->url);
                Storage::delete($oldPath);
            }

            $path = $request->file('file')->store('documents','public');
            $validated['url'] = $path;
        }

        $document->update($validated);

        return response()->json($document);
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(string $id)
    {
        $document = Document::findOrFail($id);

        // Delete associated file
        if ($document->url) {
            $path = str_replace('/storage', 'public', $document->url);
            Storage::delete($path);
        }

        $document->delete();

        return response()->json(null, 204);
    }

    public function getByEdition($editionId)
    {
        $documents = Document::where('edition_id', $editionId)->get();
        return response()->json($documents);
    }
}