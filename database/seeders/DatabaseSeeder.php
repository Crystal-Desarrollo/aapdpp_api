<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Link;
use App\Models\Role;
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


        if (env('APP_ENV') === 'local') {
            Article::factory()->count(10)->create();
            Link::factory()->count(15)->create();

            \App\Models\User::factory()->create([
                'name' => 'Test User',
                'email' => 'user@example.com',
                'role' => 2
            ]);

            \App\Models\User::factory()->create([
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'role' => 1
            ]);
        }

        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'member']);
    }
}
