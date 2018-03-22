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

// Authentication Routes.
//-------------------------------------------------------------------------
Route::post('userLogin','UserController@userLogin');

   Route::post('userRegister','UserController@userRegister');
   Route::get('userDetails','UserController@userDetails');
   Route::post('/{user}/userDestroy','UserController@userDestroy');


Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function() {
    //all other api routes goes here 
   Route::post('userRegister','UserController@userRegister');
   Route::get('userDetails','UserController@userDetails');
   Route::post('/{user}/userDestroy','UserController@userDestroy');

});
//--------------------------------------------------------------------------
//Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function() {
//});

//Issue Api
Route::get('Issue', 'IssueController@index');
Route::get('Issue/{issue}', 'IssueController@show');
Route::post('addIssue', 'IssueController@addIssue');
Route::patch('updateIssue/{iss_id}', 'IssueController@updateIssue');
