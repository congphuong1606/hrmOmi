<?php

use App\Models\EmployeeExcelPosition;
use Illuminate\Database\Seeder;

class EmployeeExcelPositionTableSeeder extends Seeder
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
            $position = new EmployeeExcelPosition();
            $position->name = 'Developer';
            $position->save();

            $position2 = new EmployeeExcelPosition();
            $position2->name = 'Trưởng phòng';
            $position2->save();

            $position3 = new EmployeeExcelPosition();
            $position3->name = 'Nhân viên đào tạo';
            $position3->save();

            $position4 = new EmployeeExcelPosition();
            $position4->name = 'PM';
            $position4->save();

            $position5 = new EmployeeExcelPosition();
            $position5->name = 'Hành Chính';
            $position5->save();

            $position6 = new EmployeeExcelPosition();
            $position6->name = 'Android';
            $position6->save();

            $position7 = new EmployeeExcelPosition();
            $position7->name = 'JAVA';
            $position7->save();

            $position8 = new EmployeeExcelPosition();
            $position8->name = 'C#';
            $position8->save();

            $position9 = new EmployeeExcelPosition();
            $position9->name = 'PHP';
            $position9->save();

            $position10 = new EmployeeExcelPosition();
            $position10->name = 'Tester';
            $position10->save();

            $position11 = new EmployeeExcelPosition();
            $position11->name = 'JQC';
            $position11->save();

            $position12 = new EmployeeExcelPosition();
            $position12->name = 'Comtor';
            $position12->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
