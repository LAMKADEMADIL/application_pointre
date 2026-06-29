<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('worker')->orderBy('date', 'desc')->get();
        return response()->json($attendances, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'date' => 'required|date',
            'morning_entry' => 'nullable|date_format:H:i',
            'morning_exit' => 'nullable|date_format:H:i',
            'afternoon_entry' => 'nullable|date_format:H:i',
            'afternoon_exit' => 'nullable|date_format:H:i',
            'total_minutes' => 'integer|min:0'
        ]);

        $attendance = Attendance::create($validatedData);

        return response()->json(['message' => 'Attendance logged successfully', 'attendance' => $attendance], 201);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) return response()->json(['message' => 'Attendance not found'], 404);

        $validatedData = $request->validate([
            'morning_entry' => 'nullable|date_format:H:i',
            'morning_exit' => 'nullable|date_format:H:i',
            'afternoon_entry' => 'nullable|date_format:H:i',
            'afternoon_exit' => 'nullable|date_format:H:i',
            'total_minutes' => 'integer|min:0'
        ]);

        $attendance->update($validatedData);

        return response()->json(['message' => 'Attendance updated', 'attendance' => $attendance], 200);
    }
}
