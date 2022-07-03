<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Link;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if (env('APP_ENV') !== 'production') {
            Article::factory()->count(10)->create();
            Link::factory()->count(15)->create();
        }

        Role::factory()->create(['name' => 'admin']);
        Role::factory()->create(['name' => 'member']);
    }
}
