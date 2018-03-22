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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('userLogin','UserController@userLogin');
Route::post('userRegister','UserController@userRegister');
Route::get('userDetails','UserController@userDetails');
Route::post('/{user}/userDestroy','UserController@userDestroy');


//Issue Api
Route::get('Issue', 'IssueController@index');
Route::get('Issue/{issue}', 'IssueController@show');
Route::post('addIssue', 'IssueController@addIssue');
Route::patch('updateIssue/{iss_id}', 'IssueController@updateIssue');
