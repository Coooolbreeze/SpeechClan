<?php

use Illuminate\Database\Seeder;

class UserAuthsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\UserAuth::create([
            'id' => 1,
            'user_id' => 1,
            'platform' => 'local',
            'identity_type' => 'account',
            'identifier' => 'administrator',
            'credential' => Hash::make('adminSecret'),
            'verified' => 1
        ]);
    }
}
