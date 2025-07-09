<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Category;
use App\Models\Employee;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //      $attendances = Attendance::with('employee')->orderBy('date', 'desc')->get();
    // return view('attendance.index', compact('attendances'));

    // }
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.category', 'schedule']);

        if ($request->filled('category_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }

        $attendances = $query->orderBy('date', 'desc')->orderBy('time', 'desc')->paginate(10);

        $categories = Category::all();
        $employees = Employee::all();

        return view('attendance.index', compact('attendances', 'categories', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // Validate attendance data
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'location' => 'required|string',
        'time' => 'required|date',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Face image for verification
    ]);

    // Convert face image to base64 for verification
    $faceImage = base64_encode(file_get_contents($request->file('photo')));

    // Verify the face using the Presence API
    $response = Http::post('https://presence.guestallow.com/api/face/verifyFace', [
        'face_image' => $faceImage,
    ]);

    // Check if face verification is successful
    if ($response->successful()) {
        // Face verification successful, proceed with recording attendance
        $attendance = new Attendance([
            'employee_id' => $validated['employee_id'],
            'location' => $validated['location'],
            'time' => $validated['time'],
        ]);

        if ($attendance->save()) {
            return response()->json(['message' => 'Attendance recorded successfully!']);
        } else {
            return response()->json(['error' => 'Failed to record attendance.'], 500);
        }
    } else {
        // If face verification fails
        return response()->json(['error' => 'Face verification failed.'], 400);
    }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
