<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
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
            $setting = new Setting();
            $setting->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
