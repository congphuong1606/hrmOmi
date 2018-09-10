<?php

use App\Models\JobStatus;
use Illuminate\Database\Seeder;

class AddTrainingToJobStatusTableSeeder extends Seeder
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
            $training = new JobStatus();
            $training->code = JOS_STATUS_CODE_TRAINING;
            $training->description = 'Thử việc';
            $training->name = 'Thử việc';
            $training->save();

            $collaborator = new JobStatus();
            $collaborator->code = JOS_STATUS_CODE_COLLABORATOR;
            $collaborator->description = 'Cộng tác viên';
            $collaborator->name = 'Cộng tác viên';
            $collaborator->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
