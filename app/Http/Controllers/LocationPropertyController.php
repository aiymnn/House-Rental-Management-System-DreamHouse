<?php

namespace App\Http\Controllers;

use App\Models\LocationProperty;
use Illuminate\Http\Request;

class LocationPropertyController extends Controller
{
    public function create()
    {
        return view('location_properties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        LocationProperty::create([
            'property_id' => $request->property_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('location_properties.create')->with('success', 'Location property saved successfully.');
    }

    public function index()
    {
        $locationProperties = LocationProperty::all();
        return view('location_properties.index', compact('locationProperties'));
    }
}
