<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentAvailability;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class StudentAvailabilityController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'monday' => 'required|boolean',
            'tuesday' => 'required|boolean',
            'wednesday' => 'required|boolean',
            'thursday' => 'required|boolean',
            'friday' => 'required|boolean',
            'saturday' => 'required|boolean',
            'sunday' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=>false,'message' => $validator->errors()]);
        }
        // Retrieve the validated data
        $validatedData = $validator->validated();
        
        // Use updateOrCreate method to either update or create a new record
        $availability = StudentAvailability::updateOrCreate(
            ['student_id' => $validatedData['student_id']], // Condition to check for existing record
            $validatedData // Data to update or create with
        );

        return response()->json(['success'=>true,
                                'message' => $availability->wasRecentlyCreated
                                    ? 'Student availability created successfully.'
                                    : 'Student availability updated successfully.',
                                'data' => $availability]);
    }
}