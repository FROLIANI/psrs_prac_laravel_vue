<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\job;
use Illuminate\Http\Request;


class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all jobs posted
		$jobs = Job::where('is_active',true)->get();
		
		try{
			return response()->json([
			'status'=>true,
			'code'=>201,
			'message'=>'Jobs Fetched successfuly',
			'data'=>$jobs,
			
			],201);
		}
		catch (\Exception $e){
			$errorId = now()->format('YmdHis').rand(1000, 99999);
			
			\Log::error("[$errorId] Failed to fetch posted jobs:" .$e->getMessage(),[
			'trace'=>$e->getTraceAsString()
			]);
			
			return response()->json([
			'status'=>false,
			'code'=>500,
			'message'=>'Job failed to fetch, please contact with Administrator with errorId $errorId'
			]);
			
		}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
		$this->authorize('create', Job::class);
		
		$validated = $request->validate([
		'title'=> 'required|string|max:255',
		'department'=> 'required|string|max:255',
		'location'=>'required|string|max:255',
		'salary'=>'required|numeric|min:0'
		]);
		
		try{
			$job = Job::create($validated);
			return response()->json([
			'status'=>true,
			'code'=>201,
			'message'=>'Job Created Successful',
			'data'=>$job
			],201);
			
		}
		catch (\Exception $e){
			$errorId = now()->format('YmdHis').rand(1000, 9999);
			\Log::error("[$errorId] Job Posting failed:" .$e->getMessage(),
			[
			'trace'=>$e->getTraceASString()
			]);
			
			return response()->json([
			'status'=>false,
			'code'=>500,
			'message'=>'Job posting Failed please contact Administartor with errorid $errorId'
			]);
			
		}
		
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
