<?php

namespace App\Http\Controllers;

use App\Models\FormPrices;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FormPricesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $formPrices = FormPrices::with('edition')->get();
            
            return response()->json([
                'success' => true,
                'data' => $formPrices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving form prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'edition_id' => 'required|exists:edition,id',
                'prix_tun' => 'integer|min:0',
                'prix_international' => 'integer|min:0',
                'prix_tun_hebergement' => 'integer|min:0',
                'prix_article' => 'integer|min:0',
                'prix_acc_adulte' => 'integer|min:0',
                'prix_acc_enfant' => 'integer|min:0',
                'prix_nuit_supp' => 'integer|min:0',
                'prix_single_supp' => 'integer|min:0',
            ]);

            $formPrice = FormPrices::create($validatedData);
            $formPrice->load('edition');

            return response()->json([
                'success' => true,
                'message' => 'Form prices created successfully',
                'data' => $formPrice
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating form prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $formPrice = FormPrices::with('edition')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $formPrice
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Form prices not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving form prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $formPrice = FormPrices::findOrFail($id);

            $validatedData = $request->validate([
                'edition_id' => 'sometimes|exists:edition,id',
                'prix_tun' => 'sometimes|integer|min:0',
                'prix_international' => 'sometimes|integer|min:0',
                'prix_tun_hebergement' => 'sometimes|integer|min:0',
                'prix_article' => 'sometimes|integer|min:0',
                'prix_acc_adulte' => 'sometimes|integer|min:0',
                'prix_acc_enfant' => 'sometimes|integer|min:0',
                'prix_nuit_supp' => 'sometimes|integer|min:0',
                'prix_single_supp' => 'sometimes|integer|min:0',
            ]);

            $formPrice->update($validatedData);
            $formPrice->load('edition');

            return response()->json([
                'success' => true,
                'message' => 'Form prices updated successfully',
                'data' => $formPrice
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Form prices not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating form prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $formPrice = FormPrices::findOrFail($id);
            $formPrice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Form prices deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Form prices not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting form prices',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get form prices by edition ID
     */
    public function getByEdition(string $editionId): JsonResponse
    {
        try {
            $formPrices = FormPrices::where('edition_id', $editionId)
                ->with('edition')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $formPrices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving form prices for edition',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}