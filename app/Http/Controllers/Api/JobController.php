<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('is_active', true)->get();

        try {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => 'Jobs fetched successfully',
                'data'    => $jobs,
            ], 200);
        } catch (\Exception $e) {
            $errorId = now()->format('YmdHis') . rand(1000, 99999);
            \Log::error("[$errorId] Failed to fetch jobs: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'code'    => 500,
                'message' => "Job fetch failed. Contact Administrator with error $errorId",
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', Job::class);

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'salary'     => 'required|numeric|min:0',
            'is_active'  => 'sometimes|boolean',
        ]);

        try {
            $job = Job::create($validated + [
                'created_by' => $request->user()->id,
            ]);

            return response()->json([
                'status'  => true,
                'code'    => 201,
                'message' => 'Job created successfully',
                'data'    => $job,
            ], 201);

        } catch (\Exception $e) {
            $errorId = now()->format('YmdHis') . rand(1000, 9999);
            \Log::error("[$errorId] Job posting failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'code'    => 500,
                'message' => "Job posting failed. Contact Administrator with error $errorId",
            ], 500);
        }
    }
}
