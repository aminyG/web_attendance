<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
use Illuminate\Support\Facades\Http;

class ScheduleController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not authenticated');
    }

    $categories = Category::where('user_id', $user->id)
                            ->with('schedules')  // Memuat jadwal yang terkait dengan kategori
                            ->get();

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . session('api_token'),
        'Accept' => 'application/json',
    ])->get('http://presence.guestallow.com/api/schedules');

    if ($response->successful()) {
        $schedulesFromApi = $response->json()['data'] ?? [];
        Log::info('Schedules retrieved from API', $schedulesFromApi);
    } else {
        Log::error('Failed to retrieve schedules from API', ['status' => $response->status()]);
        $schedulesFromApi = [];
    }

    $locations = Location::where('user_id', $user->id)->get();

    return view('schedule.index', compact('categories', 'locations', 'schedulesFromApi'));
}
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'order' => 'nullable|integer',
            'weekday' => 'required|integer',
        ]);

        $category = Category::find($request->category_id);

        $categoryUserId = $category->server_id;

        $location = Location::find($request->location_id);

        $schedule = Schedule::create($request->only(['category_id', 'location_id', 'name', 'start_time', 'end_time', 'order']));

        try {
            $apiResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('api_token'),
                'Accept' => 'application/json',
            ])->post('http://presence.guestallow.com/api/schedules', [
                'name' => $request->name,
                'description' => $request->description ?? '',
                'location_name' => $location->name ?? '',
                'latitude' => $location->latitude ?? '',
                'longitude' => $location->longitude ?? '',
                'radius' => $location->radius ?? 0,
                'category_user_id' => $categoryUserId,
                'schedule_details' => [
                    [
                        'weekday' => $request->weekday,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                    ]
                ]
            ]);

            if ($apiResponse->successful()) {
                $presenceId = $apiResponse->json('data.id');

                $schedule->update(['presence_id' => $presenceId]);

                Log::info('Schedule created successfully in Presence API', $apiResponse->json());
            } else {
                Log::error('Failed to create schedule in Presence API', [
                    'response' => $apiResponse->json(),
                    'status' => $apiResponse->status()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Exception during creating schedule in Presence API', [
                'message' => $e->getMessage(),
                'schedule_id' => $schedule->id ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id', 
            'name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'order' => 'nullable|integer',
            'weekday' => 'required|integer', 
        ]);

        $schedule = Schedule::findOrFail($id);

        $category = Category::find($request->category_id);
        $location = Location::find($request->location_id);

        $categoryUserId = $category->server_id; 

        $schedule->update($request->only(['category_id', 'location_id', 'name', 'start_time', 'end_time', 'order']));

        try {
            $apiResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('api_token'),
                'Accept' => 'application/json',
            ])->put("http://presence.guestallow.com/api/schedules/{$schedule->presence_id}", [
                'name' => $request->name,
                'description' => $request->description ?? '',
                'location_name' => $location->name ?? '', 
                'latitude' => $location->latitude ?? '', 
                'longitude' => $location->longitude ?? '', 
                'radius' => $location->radius ?? 0, 
                'category_user_id' => $categoryUserId,
                'schedule_details' => [
                    [
                        'weekday' => $request->weekday,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                    ]
                ]
            ]);

            if ($apiResponse->successful()) {
                Log::info('Schedule updated successfully in Presence API', $apiResponse->json());
                return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
            } else {
                Log::error('Failed to update schedule in Presence API', [
                    'response' => $apiResponse->json(),
                    'status' => $apiResponse->status()
                ]);
                return redirect()->back()->with('error', 'Gagal memperbarui jadwal.');
            }

        } catch (\Exception $e) {
            Log::error('Exception during updating schedule in Presence API', [
                'message' => $e->getMessage(),
                'schedule_id' => $schedule->id ?? null,
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui jadwal.');
        }
    }


    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);

        if ($schedule->presence_id) {
            try {
                $apiResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . session('api_token'),
                    'Accept' => 'application/json',
                ])->delete("http://presence.guestallow.com/api/schedules/{$schedule->presence_id}");

                if ($apiResponse->successful()) {
                    Log::info('Schedule deleted from Presence API', ['presence_id' => $schedule->presence_id]);
                } else {
                    Log::warning('Failed to delete schedule in Presence API', [
                        'response' => $apiResponse->json(),
                        'status' => $apiResponse->status()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Exception during deleting schedule in Presence API', [
                    'message' => $e->getMessage(),
                    'presence_id' => $schedule->presence_id,
                ]);
            }
        }

        $schedule->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
