<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Issue;
use Illuminate\Support\Facades\DB;
use Validator; 
class mail extends Controller
{
    //
    public function send(Request $request)
    {
       $iss_type = $request['iss_type'];
       $status = $request['status'];
       $building_id = $request['building_id'];
       $room_num = $request['room_num'];
       $cust_ucid = $request['cust_ucid'];
       $iss_description = $request['iss_description'];
       $front_desk_tech = $request['front_desk_tech'];
       $tech_id = $request['tech_id'];             
       $send = $request['tech_ucid'] . '@njit.edu';
          
        Mail::send('email', 
        ['building_name' => $building_id, 'room_num' => $room_num, 'iss_type'=>$iss_type,'iss_description'=>$iss_description], function ($message)
        use($send)
        {
            $message->from('mtss.ticketing@gmail.com', 'Christian Nwamba');
            $message->to($send);
        });
        return response()->json(['message' => 'Request completed']);
    }
//-----------------------------------------------------------------------------------------------------------
    public function sendNewticket(Request $request)
    {   
    }
}
