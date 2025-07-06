<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::paginate(10);
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'radius' => 'required|integer|min:1',
    //     ]);

    //     Location::create($request->only(['name', 'latitude', 'longitude', 'radius']));

    //     return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        Location::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ]);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan');
    }


    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        $location->update($request->only(['name', 'latitude', 'longitude', 'radius']));

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus');
    }
    public function setActive(Location $location)
    {
        Log::info("SetActive dipanggil untuk ID: {$location->id}");
        // Set semua jadi non-aktif
        Location::where('is_active', true)->update(['is_active' => false]);

        // Set yang dipilih jadi aktif
        $location->update(['is_active' => true]);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diaktifkan');
    }

}