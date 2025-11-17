<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Landscape;
use App\Models\SwimmingPool;
use App\Models\Renovation;
use Illuminate\Support\Facades\Validator;

class DesignController extends Controller
{
    /**
     * Save the selected design ID.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDesignId(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'design_id' => 'required|integer|exists:designs,id', // Adjust validation as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // Here you would save the design_id to your database or perform any other action
        // For example, saving to a user's session or a pivot table

        // Assuming a simple case where we just return the ID for now
        return response()->json([
            'success' => true,
            'message' => 'Design ID saved successfully!',
        ]);
    }

    public function getDesigns($type)
    {
        try {
            // Validate $type if needed
            $designs = Design::where('category', $type)->get(); // Adjust as per your model and database structure
            return response()->json($designs);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error fetching designs: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch designs'], 500);
        }
    }
}
    

