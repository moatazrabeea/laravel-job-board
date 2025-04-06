<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Services\JobFilterService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->input('filter', []);
        
        $query = JobListing::query();
 
        if($filters){

        

        try {
                $query = JobFilterService::apply($query, $filters);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid filter format: ' . $e->getMessage()], 400);
            }
      }

        return response()->json($query->get());
    }
}
