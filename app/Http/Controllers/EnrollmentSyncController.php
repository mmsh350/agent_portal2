<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollmentSyncController extends Controller
{
    public function updateStatus(Request $request)
    {

        // Validate incoming request
        $validated = $request->validate([
            'refno' => 'required|string',
            'status' => 'required|string|in:submitted,processing,successful,rejected',
            'reason' => 'nullable|string',
        ]);

        // Log the request data
        Log::info('Incoming enrollment update:', $validated);

        // You can still do the DB update if needed
        DB::table('bvn_enrollments')
            ->where('refno', $validated['refno'])
            ->update([
                'status' => $validated['status'],
                'reason' => $validated['reason'],
            ]);

        return response()->json([
            'refno' => $validated['refno'],
            'status' => true,
            'message' => 'Enrollment status updated successfully.',
        ]);
    }
}
