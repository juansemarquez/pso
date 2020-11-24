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
        // Create admin, teacher and students roles
        $admin = new \App\Models\Role(['description' => 'admin']);
        $teacher = new \App\Models\Role(['description' => 'teacher']);
        $student = new \App\Models\Role(['description' => 'student']);
        $admin->save();
        $teacher->save();
        $student->save();
        
        //Create an admin user (password: password)
        $adminUser = new \App\Models\User();
        $adminUser->name = "admin";
        //Password: password
        $adminUser->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $adminUser->email = 'admin@admin.com';
        $now = new \Datetime();
        $now->modify("+5 seconds");
        $adminUser->email_verified_at = $now->format('Y-m-d H:i:s');
        $adminUser->save();
        $adminUser->roles()->attach(\App\Models\Role::where('description', 'admin')->first());
        $adminUser->save();
    }
}
