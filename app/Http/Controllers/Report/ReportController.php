<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;




class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $findRequest = Report::with('user')->orderBy('created_at', 'desc')->get();

            return response()->json([
                'message' => 'Requests Found',
                'requests' => $findRequest,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error finding the request',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:0,1,2',
        ]);

        try {

            $newRequest = Report::create([
                'user_id' => $request->user()->id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
            ]);

            return response()->json([
                'message' => 'Request created successfully',
                'request' => $newRequest,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error creating the request',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        try {
            $findRequest = Report::where('id', $id)->with('user')->first();
            $owner = $findRequest->user_id === $request->user()->id;

            return response()->json([
                'message' => 'Request found successfully',
                'request' => $findRequest,
                'owner' => $owner
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error finding the request',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $updatedRequest = Report::where('id', $id)->update(['status' => $request->newStatus]);

            return response()->json([
                'message' => 'Request updated successfully',
                'request' => $request->newStatus
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error deleting the request',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            Report::destroy($id);

            return response()->json([
                'message' => 'Request deleted successfully',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error deleting the request',
                'error' => $e->getMessage(),
            ], 500);

        }
    }
}
