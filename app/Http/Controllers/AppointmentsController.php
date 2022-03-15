<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointments;

class AppointmentsController extends Controller
{
    // Add a new appointment
    public function store(Request $request)
    {
        // Get input data from request
        $date = $request->input('date');
        $name = $request->input('name');

        // Attempt to insert the appointment model into the database
        $appointment = Appointments::create([ 'date' => $date, 'name' => $name ]);
        if ($appointment) {
            // Successfully created
            return response('', 201);
        }

        // There was an error
        return response('', 400);
    }

    // Return an array of all appointments
    public function show()
    {
        // Order the appointments by date in order to make it easier for the client to display past and future appointments
        $appointments = Appointments::all()->sortBy('date');

        return response()->json([ 'data' => $appointments ], 200);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $date = $request->input('date');
        $name = $request->input('name');

        $appointment = Appointments::find($id);
        if ($appointment) {
            // The appointment was found
            $appointment->date = $date;
            $appointment->name = $name;
            // Write the updated record back to the database
            $appointment->save();

            return response('', 200);
        }

        // The appointment was not found or there was another error
        return response('', 400);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        $appointment = Appointments::find($id);
        if ($appointment) {
            $appointment->delete();

            return response('', 200);
        }

        return response('', 400);
    }
}
