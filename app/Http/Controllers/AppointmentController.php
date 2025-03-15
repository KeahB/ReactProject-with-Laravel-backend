<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index() {
        try {
            return response()->json(Appointment::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
       
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Appointment successfully created',
            'appointment' => $appointment
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to create appointment'], 500);
    }
}


    public function show($id)
    {
        return Appointment::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());

        return $appointment;
    }

    public function destroy($id)
    {
        return Appointment::destroy($id);
    }
}
