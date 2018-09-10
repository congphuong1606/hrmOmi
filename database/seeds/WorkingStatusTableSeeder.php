<?php

use App\Models\WorkingStatus;
use Illuminate\Database\Seeder;

class WorkingStatusTableSeeder extends Seeder
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
            $working = new WorkingStatus();
            $working->code = WORKING_STATUS_CODE_WORKING;
            $working->description = 'Đang làm';
            $working->name = 'Đang làm';
            $working->save();

            $checkout = new WorkingStatus();
            $checkout->code = WORKING_STATUS_CODE_CHECKOUT;
            $checkout->description = 'Nghỉ làm';
            $checkout->name = 'Nghỉ làm';
            $checkout->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
