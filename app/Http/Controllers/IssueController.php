<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Issue;
use Illuminate\Support\Facades\DB;


class IssueController extends Controller
{

    public function index()
    {

        //return DB::table('tickets')
        //   ->select('iss_id','iss_type','status','room_num','cust_ucid','iss_description','front_desk_tech','building_id','tech_ucid')
        // ->join('tech_issue_assignments','issue_id', '=', 'iss_id')
        //->get();

        $query = DB::table('tickets')
            ->Select('iss_id','iss_type','status','room_num','cust_ucid','iss_description','front_desk_tech','building_id',DB::raw("(group_concat(tech_ucid SEPARATOR ' ' )) as 'tech_ucid'"))
            ->join('tech_issue_assignments','issue_id', '=', 'iss_id')
            ->groupby('iss_id')
            ->get();
        //return response()->json(['success'=>$query],200);
        return $query;

    }

    public function show(Issue $issue)
    {
        //get data from DB about specified issue.
        $test =DB::table('tickets')
            ->join('tech_issue_assignments', 'issue_id', '=', 'iss_id')
            ->select('issue_id','iss_type','status','room_num','cust_ucid','iss_description','front_desk_tech','building_id','tech_ucid')
            ->where('iss_id',$issue['iss_id'])
            ->get();

        $test= json_decode($test,true);

        foreach($test as $is)
        {
            $tech_array[] = $is['tech_ucid'];

        }

        $final['issue_id'] = $issue['iss_id'];
        $final['iss_type'] = $test[0]['iss_type'];
        $final['status'] = $test[0]['status'];
        $final['room_num'] = $test[0]['room_num'];
        $final['cust_ucid'] = $test[0]['cust_ucid'];
        $final['iss_description'] = $test[0]['iss_description'];
        $final['front_desk_tech'] = $test[0]['front_desk_tech'];
        $final['building_id'] = $test[0]['building_id'];
        $final['tech_ucid'] = $tech_array;

        return $final;

    }

    public function addIssue(Request $request)
    {
         
        $insertId = DB::table('tickets')->insertGetId([
            'iss_type' => $request['iss_type'],
            'status' => $request['status'],
            'building_id' => $request['building_id'],
            'room_num' => $request['room_num'],
            'cust_ucid' => $request['cust_ucid'],
            'iss_description' => $request['iss_description'],
            'front_desk_tech' => $request['front_desk_tech']
            ]);

        DB::table('tech_issue_assignments')->insert(
            ['tech_ucid' => $request['tech_ucid'], 'issue_id'=> $insertId]);

        return response()->json('success', 200);
    }


    public function updateIssue(Request $request)
    {

       if($request['status'] =='closed' or $request['status'] =='resolved' or $request['status'] == 'unresolved' ){
           DB::table('tickets')
               ->where('iss_id', $request['iss_id'])
               ->update(['status' => $request['status']]);

       }
       elseif($request['status']== '')
       {

       }
       else{
           return  response()->json('error not a valid status', 400);
       }

        if($request['iss_type'] != '')
        {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['iss_type' => $request['iss_type']]);
        }

        if($request['building_id'] != '')
        {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['building_id' => $request['building_id']]);
        }

        if($request['room_num'] != '')
        {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['room_num' => $request['room_num']]);
        }

        if($request['cust_ucid'] != '')
        {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['cust_ucid' => $request['cust_ucid']]);
        }

        if($request['iss_description'] != '')
        {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['iss_description' => $request['iss_description']]);

        }

        if($request['front_desk_tech'] != '')
        {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['front_desk_tech' => $request['front_desk_tech']]);
        }

            return response()->json('success', 200);

    }



}

