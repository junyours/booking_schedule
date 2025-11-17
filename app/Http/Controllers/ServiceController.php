<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function showByCategory($category)
    {
        // Fetch services by category and status 'available'
        $services = Service::where('category', $category)
            ->where('status', 'available')
            ->get();
    
        // Log image paths for debugging
        foreach ($services as $service) {
            Log::info('Service Image Path: ' . $service->image);
        }
    
        // Return the view and pass the services data to it
        return view('services.view', compact('services', 'category'));
    }
    
    
}
