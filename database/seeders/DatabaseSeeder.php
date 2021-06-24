<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if (Admin::whereUsername('admin@internations.org')->doesntExist()) {
            Admin::add([
                'name' => 'Admin Admin',
                'username' => 'admin@internations.org',
                'password' => 'password',
            ]);
        }
        $groups = Group::factory()->count(10)->create();

        foreach ($groups->random(5) as $group) {
            User::factory()->count(10)->for($group)->create();
        }
    }
}
