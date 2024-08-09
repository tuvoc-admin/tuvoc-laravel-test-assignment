<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * List all students.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $students = Student::all();
        return response()->json(['success'=>true,'message'=>'success', 'data' => $students]);
    }

    /**
     * Add a new student.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date_format:d-m-Y',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $dateOfBirth = trim($request->date_of_birth);
		$formattedDateOfBirth = Carbon::createFromFormat('d-m-Y', $dateOfBirth)->format('Y-m-d');

        if ($validator->fails()) {
            return response()->json(['success'=>false,'message' => $validator->errors()]);
        }

        $student = Student::create([
	        'first_name' => $request->input('first_name'),
	        'middle_name' => $request->input('middle_name'),
	        'last_name' => $request->input('last_name'),
	        'date_of_birth' => $formattedDateOfBirth,
	        'email' => $request->input('email'),
	        'phone' => $request->input('phone'),
	    ]);
        return response()->json(['success'=>true,'message'=>'success', 'data' => $student]);
    }
}
