<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Auth Routes / API token groups
//-------------------------------------------------------------------------

Route::post('userLogin','UserController@userLogin');

Route::get('dashOne','DashboardController@dashOne');

Route::get('unresolved','DashboardController@unresolved');
Route::get('sendUpdate','MailController@sendUpdate');

Route::get('dashTwo','DashboardController@unresolved');


Route::group(['middleware' => 'auth:api'], function()
  {
  
    Route::get ('userDetails','UserController@userDetails');
    Route::post('userRegister','UserController@userRegister');
    Route::get('myDetails','UserController@myDetails');
    Route::post('addIssue', 'IssueController@addIssue');
    Route::post('/{user}/userDestroy','UserController@userDestroy');
   // Route::get('Issue', 'IssueController@index');
    Route::patch('updateIssue/{iss_id}', 'IssueController@updateIssue');
  });





//Issue API Routes
//--------------------------------------------------------------------------

//Route::get('Issue/{issue}', 'IssueController@show');
 Route::get('Issue', 'IssueController@index');
 // Route::patch('updateIssue/{iss_id}', 'IssueController@updateIssue');
 // Route::post('addIssue', 'IssueController@addIssue');
