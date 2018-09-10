<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(JobStatusTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(WorkingStatusTableSeeder::class);
        $this->call(EmployeeExcelDepartmentTableSeeder::class);
        $this->call(EmployeeExcelPositionTableSeeder::class);
        $this->call(EmployeeExcelJobStatusTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(ScreensTableSeeder::class);
        $this->call(AddTrainingToJobStatusTableSeeder::class);
        $this->call(AddTimeOffLateEarlyToScreensTableSeeder::class);
    }
}
