<?php

namespace App\Http\Controllers;

use App\Models\Landscape;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function calculatePrice(Request $request)
    {
        $landscape = Landscape::find($request->input('designable_id'));
        $region = $request->input('region');
        $complexity = $request->input('complexity');

        if ($landscape) {
            $price = $landscape->calculatePrice($region, $complexity);
            return response()->json(['price' => $price]);
        }

        return response()->json(['error' => 'Invalid designable ID'], 400);
    }
}
