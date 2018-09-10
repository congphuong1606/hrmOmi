<?php

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
            $admin = new User();
            $admin->email = ADMIN_EMAIL;
            $admin->password = 'Ominext123';
            $admin->name = 'Admin';
            $admin->save();

            $role = new Role();
            $role->name = 'ADMIN';
            $role->display_name = 'ADMIN';
            $role->description = 'ADMIN';
            $role->save();

            $teamLeader = new Role();
            $teamLeader->name = SPECIFIC_ROLE_TEAM_LEADER;
            $teamLeader->display_name = 'Trưởng phòng';
            $teamLeader->description = 'Trưởng phòng';
            $teamLeader->save();

            $pm = new Role();
            $pm->name = SPECIFIC_ROLE_PROJECT_MANAGER;
            $pm->display_name = 'PM';
            $pm->description = 'PM';
            $pm->save();

            $bom = new Role();
            $bom->name = SPECIFIC_ROLE_BOM;
            $bom->display_name = 'BOM';
            $bom->description = 'BOM';
            $bom->save();

            $roleUser = new RoleUser();
            $roleUser->user_id = $admin->id;
            $roleUser->role_id = $role->id;
            $roleUser->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}
