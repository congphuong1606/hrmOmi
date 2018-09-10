<?php

use App\Models\JobStatus;
use Illuminate\Database\Seeder;

class JobStatusTableSeeder extends Seeder
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
            $official = new JobStatus();
            $official->code = JOS_STATUS_CODE_OFFICIAL;
            $official->description = 'Chính thức';
            $official->name = 'Chính thức';
            $official->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
