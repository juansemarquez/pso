<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        $admin = new \App\Models\Role(['description' => 'admin']);
        $teacher = new \App\Models\Role(['description' => 'teacher']);
        $student = new \App\Models\Role(['description' => 'student']);
        $admin->save();
        $teacher->save();
        $student->save();
    }
}
