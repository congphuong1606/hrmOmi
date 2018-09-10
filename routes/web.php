<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/parser', "Api\EmployeeImportController@parserFile");
Route::get('/parserT', "Api\TimeOnImportController@parserFile");
Route::get('/timeon', "Api\TimeOnImportController@calculateTimeOn");
Route::get('/timeon2', "Api\TimeOnController@index");
Route::get('/truncate', "Api\TimeOnImportController@truncateImportTables");
Route::get('/months', "Api\TimeOnController@getListMonthAvailable");
Route::get('/total/months', "Api\TimeOnController@getTotalListMonthAvailable");
Route::get('/check', "Api\TimeOnController@getCheckInCheckOutMonth");
Route::get('/export', "Api\TimeOnController@export");
Route::get('/late', function() {
    echo \App\Services\TimeOnCalculating::calculatingWorkingTimeLateInDay('14:00:00');
});
Route::get('/leave-early', function() {
    echo \App\Services\TimeOnCalculating::calculatingWorkingTimeLeaveEarlyInDay('12:00:00');
});
Route::get('/working-time', function() {
    echo \App\Services\TimeOnCalculating::calculatingDayOffWorkingMonth(\Carbon\Carbon::createFromFormat('Y-m-d', '2018-07-16'));
});
Route::get('/leave-out', function() {
    echo \App\Services\TimeOnCalculating::calculatingWorkingTimeLeaveOutInDay('11:00:00', '14:00:00');
});
Route::get('/ot', function() {
    echo \App\Services\TimeOnCalculating::calculatingWorkingTimeOtInDay('08:00:00', '15:00:00');
});
Route::get('/accumulate', "Api\TimeOnController@calculatingAccumulatedYear");

Route::get('/password/{password}', function ($password) {
    return bcrypt($password);
});
Route::get('/{any?}', function () {
    return view('welcome');
});
Route::any('{all}', function(){
    return view('welcome');
})->where('all', '.*');
Config::set('debugbar.enabled', false);
