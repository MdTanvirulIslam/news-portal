<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
//use Database\Seeders\SettingsSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin321'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Editor
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@newsportal.com',
            'password' => Hash::make('password'),
            'role' => 'editor',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Reporter
        $reporter = User::create([
            'name' => 'Reporter User',
            'email' => 'reporter@newsportal.com',
            'password' => Hash::make('password'),
            'role' => 'reporter',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Categories


        // Create Subcategories


        // Create Sample Post


        $this->call([
            //SettingsSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('🔐 Admin Login: admin@newsportal.com / password');
        $this->command->info('📝 Editor Login: editor@newsportal.com / password');
        $this->command->info('✍️  Reporter Login: reporter@newsportal.com / password');
    }
}
