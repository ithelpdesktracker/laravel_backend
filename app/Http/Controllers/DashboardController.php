<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Messagebag;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use Validator;
use App\User;

class DashboardController extends Controller
{
    public function dashOne()
    {
  
        $request =DB::table('tech_issue_assignments')
        ->Select('tech_issue_assignments.tech_ucid')
  		  ->distinct()  
        ->addSelect(DB::raw('sum(case when tickets.status= "open" then 1 else 0 end) as open'))
    		->addSelect(DB::raw('sum(case when tickets.status = "resolved" then 1 else 0 end) as resolved'))
    		->addSelect(DB::raw('sum(case when tickets.status ="unresolved" then 1 else 0 end) as unresolved'))
       		->from('tech_issue_assignments')
      		->rightjoin('tickets', function($join) {
      			$join->on('tech_issue_assignments.issue_id','=', 'tickets.iss_id');
      			})
      		->groupBy('tech_issue_assignments.tech_ucid')      	
         ->get();     


        return response()->json([$request], 200);

    }
    
    public function unresolved()
    {
        
        $request = DB::table('tickets')
            ->Select(DB::raw('count(status) as Total_Unresolved'))
            ->from('tickets')
            ->where('status', '=', 'unresolved')
            ->get();


        return response()->json([$request], 200);

    
    }
}
