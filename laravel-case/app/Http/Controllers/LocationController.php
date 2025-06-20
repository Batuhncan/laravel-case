<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Requests\LocationCheckValidate;

class LocationController extends Controller
{
    // Konum Ekleme
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'marker_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $location = Location::create($data);
        return response()->json($location, 201);
    }

    // Konumları Listeleme
    public function index()
    {
        return Location::all();
    }

    // Konum Detayı
    public function show($id)
    {
        return Location::findOrFail($id);
    }

    // Konum Güncelleme
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'marker_color' => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $location->update($data);
        return response()->json($location);
    }

    // Rotalama: Gönderilen konuma en yakın lokasyonları listeler
    public function route(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;

        $locations = Location::all()->map(function ($location) use ($lat, $lng) {
            $earthRadius = 6371; // km cinsinden dünya yarıçapı

            // Konumlar arasındaki mesafe radian cinsine çeviren kısım
            $dLat = deg2rad($location->latitude - $lat);
            $dLng = deg2rad($location->longitude - $lng);

            // Haversine formulu
            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat)) * cos(deg2rad($location->latitude)) *
                sin($dLng / 2) * sin($dLng / 2);

            // Mesafeyi hesaplayan kısım
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            // Mesafeyi kilometre cinsine çeviren kısım
            $distance = $earthRadius * $c;

            // Konumların mesafelerini hesaplayan kısım
            $location->distance_km = round($distance, 2); // Mesafe km cinsinden

            return $location;
        });

        // Konumların mesafelerini kucukten buyuge sıralayan kısım
        $sorted = $locations->sortBy('distance_km')->values();

        // sıralanan mesafeleri gönderen kısım
        return response()->json($sorted);
    }

}
