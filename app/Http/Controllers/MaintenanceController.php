<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Service;

class MaintenanceController extends Controller
{
    public function index()
    {
        $services = Maintenance::with('category')->get();
        return view('maintenance.index', ['services' => $services]);
    }

    public function create()
    {
        $categories = Service::all();
        return view('maintenance.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'design' => 'required|string|max:255',
            'price' => 'required|numeric',
            'design_complexity' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
        ]);

        Maintenance::create($request->all());

        return redirect()->route('maintenance')->with('success', 'Service added successfully.');
    }

    public function edit($id)
    {
        $service = Maintenance::findOrFail($id);
        $categories = Service::all();
        return view('maintenance.edit', ['service' => $service, 'categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'design' => 'required|string|max:255',
            'price' => 'required|numeric',
            'design_complexity' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
        ]);

        $service = Maintenance::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('maintenance')->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        Maintenance::destroy($id);
        return redirect()->route('maintenance')->with('success', 'Service deleted successfully.');
    }
}
