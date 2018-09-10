<?php

use App\Models\EmployeeExcelDepartment;
use Illuminate\Database\Seeder;

class EmployeeExcelDepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        try {
            DB::beginTransaction();
            $department = new EmployeeExcelDepartment();
            $department->name = 'Phòng Product';
            $department->save();

            $department2 = new EmployeeExcelDepartment();
            $department2->name = 'Phòng BrSE & PM';
            $department2->save();

            $department3 = new EmployeeExcelDepartment();
            $department3->name = 'Phòng Hành chính Nhân sự';
            $department3->save();

            $department4 = new EmployeeExcelDepartment();
            $department4->name = 'Phòng Mobile';
            $department4->save();

            $department5 = new EmployeeExcelDepartment();
            $department5->name = 'Phòng System development';
            $department5->save();

            $department6 = new EmployeeExcelDepartment();
            $department6->name = 'Phòng QA & QC';
            $department6->save();

            $department7 = new EmployeeExcelDepartment();
            $department7->name = 'Team Comtor';
            $department7->save();

            $department8 = new EmployeeExcelDepartment();
            $department8->name = 'Phòng Business Promotion';
            $department8->save();

            $department8 = new EmployeeExcelDepartment();
            $department8->name = 'Ban Giám đốc';
            $department8->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
