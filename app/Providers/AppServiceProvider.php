<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        Schema::defaultStringLength(191);
        $this->registerValidators();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);

            $this->app->alias('Debugbar', \Barryvdh\Debugbar\Facade::class);
        }
    }

    public function registerValidators()
    {
        Validator::extend('cv_date', 'App\Validators\DateTimeValidator@ruleDate');
        Validator::replacer('cv_date', 'App\Validators\DateTimeValidator@messageDate');

        Validator::extend('cv_time', 'App\Validators\DateTimeValidator@ruleTime');
        Validator::replacer('cv_time', 'App\Validators\DateTimeValidator@messageTime');

        Validator::extend('cv_unique_employee_email', 'App\Validators\EmployeeValidator@ruleUniqueEmail');
        Validator::replacer('cv_unique_employee_email', 'App\Validators\EmployeeValidator@messageUniqueEmail');

        Validator::extend('cv_unique_employee_code', 'App\Validators\EmployeeValidator@ruleUniqueEmployeeCode');
        Validator::replacer('cv_unique_employee_code', 'App\Validators\EmployeeValidator@messageUniqueEmployeeCode');

        Validator::extend('cv_unique_attendance_code', 'App\Validators\EmployeeValidator@ruleUniqueAttendanceCode');
        Validator::replacer('cv_unique_attendance_code', 'App\Validators\EmployeeValidator@messageUniqueAttendanceCode');

        Validator::extend('cv_exist_department_id', 'App\Validators\DepartmentValidator@ruleExistDepartmentId');
        Validator::replacer('cv_exist_department_id', 'App\Validators\DepartmentValidator@messageExistDepartmentId');

        Validator::extend('cv_exist_position_id', 'App\Validators\PositionValidator@ruleExistPositionId');
        Validator::replacer('cv_exist_position_id', 'App\Validators\PositionValidator@messageExistPositionId');

        Validator::extend('cv_exist_job_status_id', 'App\Validators\JobStatusValidator@ruleExistJobStatusId');
        Validator::replacer('cv_exist_job_status_id', 'App\Validators\JobStatusValidator@messageExistJobStatusId');

        Validator::extend('cv_exist_working_status_id', 'App\Validators\WorkingStatusValidator@ruleExistWorkingStatusId');
        Validator::replacer('cv_exist_working_status_id', 'App\Validators\WorkingStatusValidator@messageExistWorkingStatusId');
    }
}
