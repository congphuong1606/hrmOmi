<?php

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
Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'test'], function () {
        Route::post('/getPdf', 'TestController@getWordFromHtml');
        Route::post('/image', 'TestController@uploadImage');
        Route::get('/time/{course_id}', 'TestController@time');
        Route::get('/range', 'TestController@rangeCompution');
        Route::get('/request', 'TestController@request');
    });

    Route::post('auth/register', 'AuthenticateController@register');
    Route::post('auth/logout', 'AuthenticateController@logout');
    Route::post('auth/login', 'AuthenticateController@login');
    Route::get('auth/notify', 'AuthenticateController@notify');
    Route::post('auth/password/forget', 'UsersController@sendMailForgetPassword');
    Route::post('auth/password/forget/verify', 'UsersController@verifyCodeFromEmail');
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('/change-password', 'AuthenticateController@changePassword');
        Route::get('/extra', 'EmployeesController@getExtraInfo');
        Route::get('user-info', 'AuthenticateController@getUserInfo');

        Route::group(['prefix' => 'users'], function () {
            Route::post('/search', 'UsersController@getSearchedList');
            Route::get('/{id}/urls', 'UsersController@getUserScreenUrl');
            Route::get('/', 'UsersController@getList');
            Route::post('/', 'UsersController@store');
            Route::get('/{id}', 'UsersController@show');
            Route::put('/{id}', 'UsersController@update');
            Route::delete('/{id}', 'UsersController@destroy');
            Route::get('/{id}/roles', 'UsersController@getUserRole');
            Route::get('/{user_id}/attach/role/{role_id}', 'UsersController@attachRole');
            Route::get('/{user_id}/detach/role/{role_id}', 'UsersController@detachRole');
            Route::get('/news/own', 'UsersController@getUserNews');
            Route::get('/courses/own', 'UsersController@getUserCourses');
            Route::get('/check/email', 'UsersController@checkEmail');
            Route::get('/email/find', 'UsersController@findEmail');

        });

        Route::group(['prefix' => 'employees'], function () {
            Route::get('/homepage', 'EmployeesController@getHomepageInfo');
            Route::get('/files/download', 'EmployeesController@downloadAttachFile');
            Route::post('search', 'EmployeesController@getSearchedList');
            Route::get('/', 'EmployeesController@index');
            Route::post('/', 'EmployeesController@store');
            Route::get('/{id}', 'EmployeesController@show');
            Route::post('/{id}/update', 'EmployeesController@update');
            Route::delete('/{id}', 'EmployeesController@destroy');
            Route::get('/{id}/department', 'EmployeesController@getEmployeeDepartment');
            Route::get('/{id}/position', 'EmployeesController@getEmployeePosition');
            Route::get('/{id}/job_status', 'EmployeesController@getEmployeeJobStatus');
            Route::get('/time_off/own', 'EmployeesController@getEmployeeTimeOff');
            Route::get('/{id}/time_on', 'EmployeesController@getEmployeeTimeOn');
            Route::get('/in_month/birthday', 'EmployeesController@getBirthdayInMonth');
            Route::get('/time_off/remaining', 'EmployeesController@getTimeOffRemaining');
            Route::post('/validate/email-exist-create', 'EmployeesController@validateEmailCreate');
            Route::post('/validate/email-exist-update', 'EmployeesController@validateEmailUpdate');
            Route::post('/validate/employee-code-exist-create', 'EmployeesController@validateEmployeeCodeCreate');
            Route::post('/validate/employee-code-exist-update', 'EmployeesController@validateEmployeeCodeUpdate');

            Route::get('/resource/employees', 'EmployeeResourcesController@getListEmployees');
            Route::get('/resource/direct-managers', 'EmployeeResourcesController@getListDirectManagers');
            Route::put('/resource/employees/{id}/direct-manager', 'EmployeeResourcesController@updateDirectManager');
            Route::get('/resource/project-managers', 'EmployeeResourcesController@getListProjectManagers');
            Route::put('/resource/employees/{id}/project-manager', 'EmployeeResourcesController@updateProjectManager');
        });

        Route::group(['prefix' => 'employee-excel'], function () {
            Route::post('/files/upload', 'EmployeeImportController@uploadFileEmployee');
            Route::get('/departments', 'EmployeeImportController@getEmployeeExcelDepartments');
            Route::post('/departments/apply', 'EmployeeImportController@applyEmployeeExcelDepartment');
            Route::get('/job_status', 'EmployeeImportController@getEmployeeExcelJobStatus');
            Route::post('/job_status/apply', 'EmployeeImportController@applyEmployeeExcelJobStatus');
            Route::get('/positions', 'EmployeeImportController@getEmployeeExcelPositions');
            Route::post('/positions/apply', 'EmployeeImportController@applyEmployeeExcelPosition');
            Route::get('/files', 'EmployeeImportController@getEmployeeExcelFiles');
            Route::get('/files/{id}/download', 'EmployeeImportController@downloadEmployeeExcelFile');
            Route::get('/files/{id}/parse', 'EmployeeImportController@parseFile');
            Route::post('/files/{id}/apply', 'EmployeeImportController@applyFile');
            Route::get('/files/{id}', 'EmployeeImportController@showEmployeeExcelFile');
        });

        Route::group(['prefix' => 'time-on-excel'], function () {
            Route::post('/files/upload', 'TimeOnImportController@uploadFileTimeOn');
            Route::get('/files', 'TimeOnImportController@getTimeOnExcelFiles');
            Route::get('/files/{id}/download', 'TimeOnImportController@downloadTimeOnExcelFile');
            Route::get('/files/{id}/parse', 'TimeOnImportController@parseFile');
            Route::post('/files/{id}/apply', 'TimeOnImportController@applyFile');
            Route::get('/files/{id}', 'TimeOnImportController@showTimeOnExcelFile');
        });

        Route::group(['prefix' => 'time-on'], function () {
            Route::get('/', 'TimeOnController@index');
            Route::get('/employee/{id}', 'TimeOnController@getTimeOnEmployee');
            Route::get('/months', 'TimeOnController@getListMonthAvailable');
            Route::get('/checkincheckout', 'TimeOnController@getCheckInCheckOutMonth');
            Route::get('/checkincheckout/{id}', 'TimeOnController@showCheckInCheckOut');
            Route::put('/checkincheckout/{id}', 'TimeOnController@updateCheckInCheckOut');
            Route::post('/calculating', 'TimeOnController@calculating');
            Route::get('/total/months', 'TimeOnController@getTotalListMonthAvailable');
            Route::get('/total/months/download/excel', 'TimeOnController@downloadTotalListMonthAvailable');
            Route::get('/total/months/employee/{id}/download/excel', 'TimeOnController@downloadTotalListMonthAvailableEmployee');
            Route::get('/total/months/employee/{id}', 'TimeOnController@getTotalListMonthAvailableEmployee');
            Route::get('/total/accumulated/years', 'TimeOnController@getTotalListYearAccumulatedAvailable');
            Route::get('/total/accumulated', 'TimeOnController@getTotalListAccumulate');
            Route::post('/total/accumulated/{id}/add', 'TimeOnController@addDayOffAccumulated');
            Route::delete('/total/accumulated/additions/{id}', 'TimeOnController@removeAdditionAccumulated');
            Route::post('/total/months/{id}/note', 'TimeOnController@updateNote');
        });

        Route::group(['prefix' => 'roles'], function () {
            Route::get('/{id}/screens', 'RolesController@getRoleScreen');
            Route::get('/{id}/users', 'RolesController@getRoleUser');
            Route::get('/', 'RolesController@index');
            Route::post('/', 'RolesController@store');
            Route::get('/{id}', 'RolesController@show');
            Route::put('/{id}', 'RolesController@update');
            Route::delete('/{id}', 'RolesController@destroy');
            Route::post('/{role_id}/attach/users', 'RolesController@attachUsers');
            Route::post('/{role_id}/detach/users', 'RolesController@detachUsers');
            Route::post('/{role_id}/attach/screens', 'RolesController@attachScreens');
//            Route::post('/{role_id}/detach/screens', 'RolesController@detachScreens');
        });
//
//        Route::group(['prefix' => 'permissions'], function () {
//            Route::get('/', 'PermissionsController@index');
//            Route::post('/', 'PermissionsController@store');
//            Route::get('/{id}', 'PermissionsController@show');
//            Route::put('/{id}', 'PermissionsController@update');
//            Route::delete('/{id}', 'PermissionsController@destroy');
//        });
        Route::group(['prefix' => 'departments'], function () {
            Route::get('/', 'DepartmentsController@index');
            Route::post('/', 'DepartmentsController@store');
            Route::get('/{id}', 'DepartmentsController@show');
            Route::put('/{id}', 'DepartmentsController@update');
            Route::delete('/{id}', 'DepartmentsController@destroy');
        });

        Route::group(['prefix' => 'job_status'], function () {
            Route::get('/', 'JobStatusController@index');
            Route::post('/', 'JobStatusController@store');
            Route::get('/{id}', 'JobStatusController@show');
            Route::put('/{id}', 'JobStatusController@update');
            Route::delete('/{id}', 'JobStatusController@destroy');
        });
        Route::group(['prefix' => 'positions'], function () {
            Route::get('/', 'PositionsController@index');
            Route::post('/', 'PositionsController@store');
            Route::get('/{id}', 'PositionsController@show');
            Route::put('/{id}', 'PositionsController@update');
            Route::delete('/{id}', 'PositionsController@destroy');
        });

        Route::group(['prefix' => 'history'], function () {
            Route::group(['prefix' => 'employees'], function () {
                Route::get('/', 'HistoryController@indexEmployeeUpdate');
                Route::get('/{id}', 'HistoryController@showEmployeeUpdate');
                Route::post('/{id}/approve', 'HistoryController@approveEmployeeUpdate');
                Route::post('/{id}/reject', 'HistoryController@approveEmployeeUpdate');
                Route::post('/approve', 'HistoryController@approveListEmployeeUpdate');
                Route::post('/reject', 'HistoryController@rejectListEmployeeUpdate');
            });

            Route::group(['prefix' => 'job_status'], function () {
                Route::get('/', 'HistoryController@indexJobStatusUpdate');
                Route::get('/{id}', 'HistoryController@showJobStatusUpdate');
                Route::put('/{id}', 'HistoryController@updateJobStatusUpdate');
            });
        });
        Route::group(['prefix' => 'timesheet'], function () {
            Route::group(['prefix' => 'time_on'], function () {
                Route::get('/', 'TimesheetController@indexTimeOn');
                Route::post('/', 'TimesheetController@storeTimeOn');
                Route::get('/{id}', 'TimesheetController@showTimeOn');
                Route::put('/{id}', 'TimesheetController@updateTimeOn');
                Route::delete('/{id}', 'TimesheetController@destroyTimeOn');
                Route::get('/employee/{id}/month/{month}/year/{year}', 'TimesheetController@getTotalTimeOn');
            });
            Route::group(['prefix' => 'time_off'], function () {
                Route::get('/', 'TimesheetController@indexTimeOff');
                Route::post('/', 'TimesheetController@storeTimeOff');
                Route::get('/{id}', 'TimesheetController@showTimeOff');
                Route::put('/{id}', 'TimesheetController@updateTimeOff');
                Route::delete('/{id}', 'TimesheetController@destroyTimeOff');
                Route::get('/approver/approve', 'TimesheetController@getApproveTimeOff');
                Route::post('/approve', 'TimesheetController@approveTimeOff');
                Route::post('/refuse', 'TimesheetController@refuseTimeOff');
                Route::post('/import', 'TimesheetController@importTimeoff');
                Route::post('/import', 'TimesheetController@importTimeoff');
            });
            Route::group(['prefix' => 'time_off_excel_files'], function () {
                Route::post('/', 'TimeOffImportController@importTimeoff');
                Route::get('/', 'TimeOffImportController@getListFile');
                Route::delete('/{id}', 'TimeOffImportController@delete');
            });
            Route::group(['prefix' => 'over_times'], function () {
                Route::get('/', 'OverTimeController@index');
                Route::post('/', 'OverTimeController@store');
                Route::get('/{id}', 'OverTimeController@show');
                Route::put('/{id}', 'OverTimeController@update');
                Route::delete('/{id}', 'OverTimeController@destroy');
                Route::get('/approver/approve', 'OverTimeController@getApproveOverTime');
                Route::post('/approve', 'OverTimeController@approveOverTime');
                Route::post('/refuse', 'OverTimeController@refuseOverTime');
            });
        });

        Route::group(['prefix' => 'screen'], function () {
            Route::get('/', 'ScreenController@index');
            Route::post('/', 'ScreenController@store');
            Route::get('/{id}', 'ScreenController@show');
            Route::put('/{id}', 'ScreenController@update');
            Route::delete('/{id}', 'ScreenController@destroy');
        });

        Route::group(['prefix' => 'screen_category'], function () {
            Route::get('/', 'ScreenCategoryController@index');
            Route::post('/', 'ScreenCategoryController@store');
            Route::get('/{id}', 'ScreenCategoryController@show');
            Route::put('/{id}', 'ScreenCategoryController@update');
            Route::delete('/{id}', 'ScreenCategoryController@destroy');
            Route::get('/all/screens', 'ScreenCategoryController@getAllCategoryInfo');
        });

        Route::group(['prefix' => 'working_status'], function () {
            Route::get('/', 'WorkingStatusController@index');
            Route::post('/', 'WorkingStatusController@store');
            Route::get('/{id}', 'WorkingStatusController@show');
            Route::put('/{id}', 'WorkingStatusController@update');
            Route::delete('/{id}', 'WorkingStatusController@destroy');
        });

        Route::group(['prefix' => 'job_positions'], function () {
            Route::get('/', 'JobPositionsController@index');
            Route::post('/', 'JobPositionsController@store');
            Route::get('/{id}', 'JobPositionsController@show');
            Route::put('/{id}', 'JobPositionsController@update');
            Route::delete('/{id}', 'JobPositionsController@destroy');
        });

        Route::group(['prefix' => 'specialized_skills'], function () {
            Route::get('/', 'SpecializedSkillsController@index');
            Route::post('/', 'SpecializedSkillsController@store');
            Route::get('/{id}', 'SpecializedSkillsController@show');
            Route::put('/{id}', 'SpecializedSkillsController@update');
            Route::delete('/{id}', 'SpecializedSkillsController@destroy');
        });
        Route::group(['prefix' => 'other_categories'], function () {
            Route::get('/', 'OtherCategoriesController@index');
            Route::post('/', 'OtherCategoriesController@store');
            Route::get('/{id}', 'OtherCategoriesController@show');
            Route::put('/{id}', 'OtherCategoriesController@update');
            Route::delete('/{id}', 'OtherCategoriesController@destroy');
        });
        Route::group(['prefix' => 'official_holidays'], function () {
            Route::get('/', 'OfficialHolidayController@index');
            Route::post('/', 'OfficialHolidayController@store');
            Route::get('/{id}', 'OfficialHolidayController@show');
            Route::put('/{id}', 'OfficialHolidayController@update');
            Route::delete('/{id}', 'OfficialHolidayController@destroy');
            Route::delete('/{id}', 'OfficialHolidayController@destroy');
        });
        Route::group(['prefix' => 'devices'], function () {
            Route::get('/', 'NotificationController@indexFcmDeviceToken');
            Route::post('/', 'NotificationController@storeFcmDeviceToken');
            Route::delete('/{id}', 'NotificationController@delete');
        });

        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', 'NotificationController@index');
            Route::get('/{id}/mark_as_read', 'NotificationController@markAsRead');
            Route::delete('/{id}', 'NotificationController@deleteNotification');
        });
        Route::group(['prefix' => 'news'], function () {
            Route::post('/', 'NewsController@store');
            Route::put('/{id}', 'NewsController@update');
            Route::delete('/{id}', 'NewsController@destroy');
            Route::post('/{id}/attach/users', 'NewsController@attachUsers');
            Route::post('/{id}/detach/users', 'NewsController@detachUsers');
        });

        Route::group(['prefix' => 'courses'], function () {
            Route::get('/', 'CoursesController@index');
            Route::post('/', 'CoursesController@store');
            Route::get('/{id}', 'CoursesController@show');
            Route::put('/{id}', 'CoursesController@update');
            Route::delete('/{id}', 'CoursesController@destroy');
            Route::get('/users/search', 'CoursesController@getSearchedUsers');
            Route::get('/{id}/qr_code', 'CoursesController@getCourseQr');
            Route::get('/{id}/training', 'CoursesController@getCourseTraining');
            Route::post('/qr/roll_up', 'CoursesController@rollUpSession');
            Route::post('/manual/roll_up', 'CoursesController@manualSessionsRollUp');
            Route::get('{id}/score/files', 'CoursesController@getListFile');
            Route::get('{id}/send_score', 'CoursesController@sendMailCourseScore');
            Route::get('/all/search', 'CoursesController@sendMailCourseScore');
        });
        Route::group(['prefix' => 'score_files'], function () {
            Route::post('/', 'CoursesScoreImportController@uploadScoreFile');
            Route::get('/{score_excel_file_id}', 'CoursesScoreImportController@readScoreFile');
            Route::get('/{score_excel_file_id}/apply', 'CoursesScoreImportController@applyScoreFile');
            Route::delete('/{score_excel_file_id}', 'CoursesScoreImportController@deleteScoreFile');
        });
        Route::group(['prefix' => 'training'], function () {
            Route::get('/', 'TrainingController@index');
            Route::post('/', 'TrainingController@store');
            Route::get('/{id}', 'TrainingController@show');
            Route::delete('/{id}', 'TrainingController@destroy');
            Route::post('/manual/mark_score', 'TrainingController@manualMarkScore');
            Route::get('/course/{course_id}/report', 'TrainingController@getTrainingReport');
        });
        Route::group(['prefix' => 'setting'], function () {
            Route::get('/', 'SettingController@show');
            Route::put('/', 'SettingController@update');
        });
        Route::resource('late_reasons', 'LateReasonController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    });
});
