<?php

use App\Models\EmployeeExcelJobStatus;
use Illuminate\Database\Seeder;

class EmployeeExcelJobStatusTableSeeder extends Seeder
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
            $jobStatus = new EmployeeExcelJobStatus();
            $jobStatus->name = 'Chính thức';
            $jobStatus->save();

            $jobStatus2 = new EmployeeExcelJobStatus();
            $jobStatus2->name = 'Thử việc';
            $jobStatus2->save();

            $jobStatus3 = new EmployeeExcelJobStatus();
            $jobStatus3->name = 'Thực tập sinh';
            $jobStatus3->save();

            $jobStatus4 = new EmployeeExcelJobStatus();
            $jobStatus4->name = 'Cộng tác viên';
            $jobStatus4->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
