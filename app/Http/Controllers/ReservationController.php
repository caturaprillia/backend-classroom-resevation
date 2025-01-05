<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Classroom;   // Tambahkan model Classroom
use App\Models\User;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    // Menambahkan reservasi baru
    public function store(Request $request)
    {
        // Validasi manual
        if (empty($request->classroom_id)) {
            return response()->json(['message' => 'Classroom ID is required'], 400);
        }

        if (empty($request->user_id)) {
            return response()->json(['message' => 'User ID is required'], 400);
        }

        if (empty($request->reservation_startTime)) {
            return response()->json(['message' => 'Reservation start time is required'], 400);
        }

        if (empty($request->reservation_endTime)) {
            return response()->json(['message' => 'Reservation end time is required'], 400);
        }

        if (isset($request->reservation_startTime) && !strtotime($request->reservation_startTime)) {
            return response()->json(['message' => 'Invalid start time format'], 400);
        }

        if (isset($request->reservation_endTime) && !strtotime($request->reservation_endTime)) {
            return response()->json(['message' => 'Invalid end time format'], 400);
        }

        if (isset($request->reservation_startTime) && isset($request->reservation_endTime) && strtotime($request->reservation_endTime) <= strtotime($request->reservation_startTime)) {
            return response()->json(['message' => 'End time must be after start time'], 400);
        }

        // Membungkus kode dalam try-catch untuk menangani error
        try {
            $reservation = Reservation::create([
                'classroom_id' => $request->classroom_id,
                'user_id' => $request->user_id,
                'reservation_startTime' => $request->reservation_startTime,
                'reservation_endTime' => $request->reservation_endTime,
                'status' => $request->status ?? 'confirmed', // Default status confirmed
            ]);

            return response()->json([
                'message' => 'Reservation created successfully!',
                'reservation' => $reservation,
            ], 201);

        } catch (QueryException $e) {
            // Menangani exception query, seperti kesalahan database
            return response()->json([
                'message' => 'Database error occurred while creating the reservation.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        } catch (\Exception $e) {
            // Menangani pengecualian umum
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    // Mendapatkan semua reservasi
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json($reservations);
    }

    // Mendapatkan detail reservasi berdasarkan ID
    public function show($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }

    // Mengupdate reservasi berdasarkan ID
    public function update(Request $request, $id)
    {
        // Cari reservasi berdasarkan ID
        $reservation = Reservation::find($id);
    
        // Jika reservasi tidak ditemukan
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    
        // Validasi manual untuk memastikan setidaknya satu field diisi
        if (!$request->hasAny(['classroom_id', 'reservation_startTime', 'reservation_endTime'])) {
            return response()->json([
                'message' => 'At least one field must be provided to update the reservation.',
            ], 400); // Bad Request
        }
    
        // Validasi manual untuk setiap field
        if ($request->has('classroom_id') && !Classroom::find($request->classroom_id)) {
            return response()->json(['message' => 'Invalid classroom ID.'], 400);
        }
    
        if ($request->has('reservation_startTime') && !strtotime($request->reservation_startTime)) {
            return response()->json(['message' => 'Invalid reservation start time format.'], 400);
        }
    
        if ($request->has('reservation_endTime') && !strtotime($request->reservation_endTime)) {
            return response()->json(['message' => 'Invalid reservation end time format.'], 400);
        }
    
        if ($request->has('reservation_startTime') && $request->has('reservation_endTime') &&
            strtotime($request->reservation_endTime) <= strtotime($request->reservation_startTime)) {
            return response()->json(['message' => 'Reservation end time must be after start time.'], 400);
        }
    
        // Update data reservasi
        try {
            $reservation->update($request->only([
                'classroom_id',
                'reservation_startTime',
                'reservation_endTime',
            ]));
    
            return response()->json([
                'message' => 'Reservation updated successfully!',
                'reservation' => $reservation,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the reservation.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }
    
    // Menghapus reservasi berdasarkan ID
    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully!']);
    }
}
