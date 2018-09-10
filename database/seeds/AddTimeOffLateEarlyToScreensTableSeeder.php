<?php

use App\Models\Screen;
use App\Models\ScreenCategory;
use Illuminate\Database\Seeder;

class AddTimeOffLateEarlyToScreensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            $timeOff = ScreenCategory::query()->where('name','OT/Nghỉ phép')->first();
            if ($timeOff === null) {
                $timeOff = new ScreenCategory();
            }
            $timeOff->name = 'OT/Nghỉ phép';
            $timeOff->description = 'OT/Nghỉ phép';
            $timeOff->display_name = 'OT/Nghỉ phép';
            $timeOff->save();
            $now = \Carbon\Carbon::now()->toDateTimeString();
            $screens = [
                [
                    'name' => 'OT/Nghỉ phép => Ra ngoài/đi muộn/về sớm',
                    'display_name' => 'OT/Nghỉ phép => Ra ngoài/đi muộn/về sớm',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/di-muon-ve-som',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ];

            Screen::query()->insert($screens);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
