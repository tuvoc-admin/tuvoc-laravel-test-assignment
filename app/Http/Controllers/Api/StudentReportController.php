<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GenerateReport;
use Illuminate\Support\Facades\Validator;

class StudentReportController extends Controller
{
    public function generateOrUpdateReport(Request $request)
{
    $data = $request->json()->all();

    $validator = Validator::make($data, [
        'student_full_name' => 'required|string',
        'session_date' => 'required|date',
        'session_minutes' => 'required|integer',
        'session_start_time' => 'required|date_format:H:i:s',
        'session_end_time' => 'required|date_format:H:i:s',
        'target_start_date' => 'required|date',
        'target_end_date' => 'required|date',
        'session_rating' => 'required|integer',
        'target' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 400);
    }

    try {
        $report = GenerateReport::orderBy('id', 'desc')->first();
        
        if ($report) { 
            $report->update([
                'student_full_name' => $data['student_full_name'],
                'session_date' => $data['session_date'],
                'session_minutes' => $data['session_minutes'],
                'session_start_time' => $data['session_start_time'],
                'session_end_time' => $data['session_end_time'],
                'target_start_date' => $data['target_start_date'],
                'target_end_date' => $data['target_end_date'],
                'session_rating' => $data['session_rating'],
                'target' => $data['target'],
            ]);

            return response()->json(['success' => true, 'message' => 'Report updated successfully', 'report' => $report], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'An error occurred', 'error' => $e->getMessage()], 500);
    }
}

}
