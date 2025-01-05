<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    // Menambahkan classroom baru
    public function store(Request $request)
    {
        // Validasi manual
        if (empty($request->name)) {
            return response()->json(['message' => 'Name is required'], 400);
        }

        if (isset($request->start_time) && !strtotime($request->start_time)) {
            return response()->json(['message' => 'Invalid start time format'], 400);
        }

        if (isset($request->end) && !strtotime($request->end)) {
            return response()->json(['message' => 'Invalid end time format'], 400);
        }

        $classroom = Classroom::create([
            'name' => $request->name,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end' => $request->end,
            'course_id' => $request->course_id,
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'message' => 'Classroom created successfully!',
            'classroom' => $classroom,
        ], 201);
    }

    // Mendapatkan semua classrooms
    public function index()
    {
        $classrooms = Classroom::all();

        return response()->json($classrooms);
    }

    // Mendapatkan detail classroom berdasarkan ID
    public function show($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], 404);
        }

        return response()->json($classroom);
    }

    // Mengupdate classroom berdasarkan ID
    public function update(Request $request, $id)
{
    $classroom = Classroom::find($id);

    if (!$classroom) {
        return response()->json(['message' => 'Classroom not found'], 404);
    }

    // Validasi manual
    if (empty($request->name)) {
        return response()->json(['message' => 'Name is required'], 400);
    }

    if (isset($request->start_time) && !strtotime($request->start_time)) {
        return response()->json(['message' => 'Invalid start time format'], 400);
    }

    if (isset($request->end) && !strtotime($request->end)) {
        return response()->json(['message' => 'Invalid end time format'], 400);
    }

    // Hanya update kolom yang ada di request
    $classroom->update(array_filter([
        'name' => $request->name,
        'location' => $request->location ?? $classroom->location,  // Menjaga nilai lama jika tidak ada
        'start_time' => $request->start_time ?? $classroom->start_time,
        'end' => $request->end ?? $classroom->end,
        'course_id' => $request->course_id ?? $classroom->course_id,
        'user_id' => $request->user_id ?? $classroom->user_id,
    ]));

    return response()->json([
        'message' => 'Classroom updated successfully!',
        'classroom' => $classroom,
    ]);
}


    // Menghapus classroom berdasarkan ID
    public function destroy($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], 404);
        }

        $classroom->delete();

        return response()->json(['message' => 'Classroom deleted successfully!']);
    }
}
