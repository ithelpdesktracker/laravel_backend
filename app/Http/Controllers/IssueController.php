<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Issue;
use Mail;
use Illuminate\Support\Facades\DB;
use Validator;


class IssueController extends Controller
{
    public $send;

    public function index()
    {
        //Query DB and return all issue and techs assigned
        $query = DB::table('tickets')
            ->Select('iss_id', 'iss_type', 'status', 'room_num', 'cust_ucid', 'iss_description', 'front_desk_tech', 'building_id', DB::raw("(group_concat(tech_ucid SEPARATOR ' ' )) as 'tech_ucid'"))
            ->join('tech_issue_assignments', 'issue_id', '=', 'iss_id')
            ->groupby('iss_id')
            ->get();
        return $query;
    }

    public function show(Issue $issue)
    {
        //get data from DB about specified issue by ID and then return it
        $test = DB::table('tickets')
            ->join('tech_issue_assignments', 'issue_id', '=', 'iss_id')
            ->select('issue_id', 'iss_type', 'status', 'room_num', 'cust_ucid', 'iss_description', 'front_desk_tech', 'building_id', 'tech_ucid')
            ->where('iss_id', $issue['iss_id'])
            ->get();

        $test = json_decode($test, true);

        //Formats DB response allowing us to put the assigned techs into an array
        foreach ($test as $is) {
            $tech_array[] = $is['tech_ucid'];

        }
        //returns techs in an array
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
        $data = $request;

        //Validator for auth system.
        $validator = Validator::make($request->all(),
            ['iss_type' => 'required',
                'status' => 'required',
                'building_id' => 'required',
                'room_num' => 'required',
                'cust_ucid' => 'required',
                'iss_description' => 'required',
                'front_desk_tech' => 'required'
            ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        //inserts new issue into db and then returns the ID number for request
        $insertId = DB::table('tickets')->insertGetId([
            'iss_type' => $request['iss_type'],
            'status' => $request['status'],
            'building_id' => $request['building_id'],
            'room_num' => $request['room_num'],
            'cust_ucid' => $request['cust_ucid'],
            'iss_description' => $request['iss_description'],
            'front_desk_tech' => $request['front_desk_tech']
        ]);
        //Takes ID number from request and then adds the assigned tech to the tech_issue_assignment table.
        DB::table('tech_issue_assignments')->insert(
            ['tech_ucid' => $request['tech_ucid'], 'issue_id' => $insertId]);

        $data['iss_id']= $insertId;
        $this->send($data);

        return response()->json('success', 200);
    }

    //--------------------------------------------------------------------------------------------------------------------------------

    public function send(Request $request)
    {
        $iss_type = $this->convertIssueType($request['iss_type']);
        $status = $request['status'];
        $building_id = $this->getBuildingName($request['building_id']);
        $room_num = $request['room_num'];
        $cust_ucid = $request['cust_ucid'];
        $iss_description = $request['iss_description'];
        $front_desk_tech = $request['front_desk_tech'];
        $iss_id = $request['iss_id'];
        $tech_ucid= $request['tech_ucid'];
        $send = $request['tech_ucid'] . '@njit.edu';

        Mail::send('email', ['building_name' => $building_id, 'room_num' => $room_num, 'iss_type' => $iss_type, 'iss_description' => $iss_description,'iss_id'=>$iss_id], function ($message) use ($send,$iss_id,$tech_ucid) {

            $message->from('mtss.ticketing@gmail.com', 'NJIT Help Desk');

            $message->to($send)->subject($iss_id." - ".$tech_ucid);

        });

        return response()->json(['message' => 'Email Sent']);
    }

    //---------------------------------------------------------------------------------------------------------------------------

    public function updateIssue(Request $request)
    {
        //This functions checks the request send for fields that need to be updated an n/a means the field is left alone
        $validator = Validator::make($request->all(), [
            'iss_type' => 'required',
            'status' => 'required',
            'building_id' => 'required',
            'room_num' => 'required',
            'cust_ucid' => 'required',
            'iss_description' => 'required',
            'front_desk_tech' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }


        if ($request['status'] == 'closed' or $request['status'] == 'resolved' or $request['status'] == 'unresolved') {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['status' => $request['status']]);

        } elseif ($request['status'] == 'n/a') {

        } else {
            return response()->json('error not a valid status', 400);
        }

        if ($request['iss_type'] != 'n/a') {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['iss_type' => $request['iss_type']]);
        }

        if ($request['building_id'] != 'n/a') {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['building_id' => $request['building_id']]);
        }

        if ($request['room_num'] != 'n/a') {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['room_num' => $request['room_num']]);
        }

        if ($request['cust_ucid'] != 'n/a') {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['cust_ucid' => $request['cust_ucid']]);
        }

        if ($request['iss_description'] != 'n/a') {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['iss_description' => $request['iss_description']]);

        }

        if ($request['front_desk_tech'] != 'n/a') {
            DB::table('tickets')
                ->where('iss_id', $request['iss_id'])
                ->update(['front_desk_tech' => $request['front_desk_tech']]);
        }

        return response()->json('success', 200);

    }

    public function getBuildingName(int $request)
    {
        $query = DB::table('buildings')->
            select('building_name')
            ->where('building_id', '=', $request)
            ->get();

        $query = json_decode($query,true);
        return $query[0]['building_name'];

    }


    public function convertIssueType(string $request)
    {
        if($request ='ca')
        {
            return 'Classroom - Audio';
        }

        if($request='cv')
        {
            return 'Classroom - Video';
        }

        if($request='cd')
        {
            return 'Classroom - Delivery';
        }

        if($request ='co')
        {
            return 'Classroom - Other';
        }

        if($request= 'se')
        {
            return 'Special Event';
        }

        if($request='o')
        {
            return 'Other';
        }

        return null;

    }



}

