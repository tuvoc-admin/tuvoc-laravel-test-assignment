<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentSession;
use App\Models\SessionNotification;
use App\Models\SessionRating;
use App\Models\StudentAvailability;
use App\Jobs\SendSessionReminderJob;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StudentSessionController extends Controller
{
    public function scheduleSession(Request $request)
    {
        // Validate the request
         $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'start_time' => 'required|date|date_format:Y-m-d H:i:s|after:now',
            'is_recurring' => 'required|boolean',
            'session_time' => 'required|integer|max:15'
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false,'message' => $validator->errors()]);
        }
        $validatedData = $validator->validated();

        $startTime = Carbon::parse($validatedData['start_time']);
        $endTime = $startTime->copy()->addMinutes($validatedData['session_time']);

        // Check if the student is available on the scheduled day
        $dayOfWeek = strtolower($startTime->format('l'));
        $availability = StudentAvailability::where('student_id', $validatedData['student_id'])->first();

        if (!$availability || !$availability->$dayOfWeek) {
            return response()->json(['success' => false, 'message' => 'Student is not available on the selected day.']);
        }

        // Ensure sessions do not overlap with existing ones
        $overlap = StudentSession::where('student_id', $validatedData['student_id'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($query) use ($startTime, $endTime) {
                          $query->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                      });
            })
            ->exists();

        if ($overlap) {
            return response()->json(['success' => false, 'message' => 'The session time overlaps with an existing session.']);
        }

        // Create the session
        $session = StudentSession::create([
            'student_id' => $validatedData['student_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_recurring' => $validatedData['is_recurring'],
        ]);

        // Schedule notifications 5 minutes before the session
        $notificationTime = $startTime->copy()->subMinutes(5);
        SessionNotification::create([
            'session_id' => $session->id,
            'student_id' => $validatedData['student_id'],
            'notification_time' => $notificationTime,
        ]);

        // Send email notifications to both the user and the student
        SendSessionReminderJob::dispatch($session)->delay($notificationTime);

        return response()->json(['success' => true, 'message' => 'Session scheduled successfully.', 'data' => $session],);
    }

    public function rateSession(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,10',
            'session_id' => 'required|exists:student_sessions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false,'message' => $validator->errors()]);
        }
        $validatedData = $validator->validated();
        $session = StudentSession::findOrFail($validatedData['session_id']);

        // Create or update the session rating
        $rating = SessionRating::updateOrCreate(
            ['session_id' => $session->id,'student_id' => $session->student_id],
            ['rating' => $validatedData['rating']]
        );

        return response()->json(['success' => true, 'message' => 'Session rated successfully.', 'data' => $rating],);
    }
}
