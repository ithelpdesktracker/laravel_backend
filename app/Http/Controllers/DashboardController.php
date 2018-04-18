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
    
      $request=DB::select('tech_issue_assignments.tech_ucid','tickets.status')
      		->addSelect(DB::raw('count(tickets.status) as total'))
      		->from('tech_issue_assignments')
      		->rightjoin('tickets', function($join) {
      			$join->on('tech_issue_assignments.issue_id', 'tickets.iss_id');
      			})
      		->groupBy('tech_issue_assignments.tech_ucid')
      		->groupBy('tickets.status')
      		->get();
         
        return response()->json(['success'=>$request], 200);
    
    
    
    }
}
