<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Issue;
use Illuminate\Support\Facades\DB;
use Validator; 
class MailController extends Controller
{
    //
    public function send(Request $request)
    {
        //$iss_description = $request['iss_description'];
       // $user = DB::table('tickets')->where('iss_description', $iss_description)->first();
    
    
    
       $iss_type = $request['iss_type'];
       $status = $request['status'];
       $building_id = $request['building_id'];
       $room_num = $request['room_num'];
       $cust_ucid = $request['cust_ucid'];
       
       $front_desk_tech = $request['front_desk_tech'];
       $tech_id = $request['tech_id'];   
       
                 
       $send = $request['tech_ucid'] . '@njit.edu';
          
        Mail::send('email', 
        ['building_name' => $building_id, 'room_num' => $room_num, 'iss_type'=>$iss_type,'iss_description'=>$iss_description], function ($message)
        use($send)
        {
            $message->from('mtss.ticketing@gmail.com', 'NJIT');
            $message->to($send);
        });
        return response()->json(['message' => 'Request completed']);
    }
//-----------------------------------------------------------------------------------------------------------
    public function sendUpdate()
    {   
        $iss_description = "buttstuff";
        //$iss_description = $request['iss_description'];
       $user = DB::table('tickets')->where('iss_description','=', $iss_description)->get();
       $iss_id =21;
       $iss_type = 'ca';
       $status = 'open';
       $building_id = 3;
       $room_num = 3047;;   
       $cust_ucid = 'jic6';;       
       $front_desk_tech = 'vcc3';   
       $iss_description ='buttstuff';  
         
       $send = $cust_ucid . '@njit.edu';          
        Mail::send('email', 
        ['building_name' => $building_id, 'room_num' => $room_num,'iss_id'=>$iss_id, 'iss_type'=>$iss_type,'iss_description'=>$iss_description], function ($message)
        use($send)
        {
            $message->from('mtss.ticketing@gmail.com', 'NJIT');
            $message->to($send);
        });
        
        return response()->json(['returned'=>$user]);
    
    }
}
