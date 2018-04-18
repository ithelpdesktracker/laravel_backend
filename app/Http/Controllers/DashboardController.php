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
    
          $request=DB::table('tech_issue_assignments')
          ->Select("tech_issue_assignments.tech_ucid","tickets.status", DB::raw("count(tickets.status) as Total"))
      		->from('tech_issue_assignments')
      		->rightjoin('tickets', function($join) {
      			$join->on('tech_issue_assignments.issue_id','=', 'tickets.iss_id');
      			})
      		->groupBy('tech_issue_assignments.tech_ucid')
      		->groupBy('tickets.status')
      		->get();

        return response()->json([$request], 200);

    }
    public function unresolved()
    {
    //convert this query
    //select count(status) as Total_Unresolved from tickets where status ='unresolved';

    
    }
}
