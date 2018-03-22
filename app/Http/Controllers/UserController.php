<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Messagebag;

use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\User;


class UserController extends Controller
{

    public function userLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success'=>$success], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
  
    public function userRegister(Request $request)
    {             
        $validator = Validator::make($request->all(), [        
            'ucid' => 'required|unique:users,ucid',
            'job_title'=>'required',
            'name' => 'required',
            'email' => 'required|email|max:255|regex:/(.*)\@njit\.edu/i|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],401);
        }
// set validated info as input for the create user function
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['api_token'] = str_random(60);       
        $user = User::create($input);
        $success['api_token'] =$user->api_token;
        $success['name'] =  $user->name;
        $success['job_title'] =$user->job_title;
        return response()->json(['success'=>$success], 200);
    }
    //--------------------------------------------------------------------------------------------
    public function userDestroy($id)
    {     
       $user = User::find($id);
       $user->delete();
       return response()->json(['Deleted' => $user],200); 
      
                   
    }

    public function userDetails()
    {
      // function will just get all user ID,Email and UCID
        $users = User::Select( 'ucid','email','id','api_token')->get();
        return response()->json(['success' => $users], 200);
    }
}

