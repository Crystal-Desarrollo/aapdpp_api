<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Link;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $env = env('APP_ENV');

        if (DB::table('roles')->count() === 0) {
            DB::table('roles')->insert(['name' => 'admin']);
            DB::table('roles')->insert(['name' => 'member']);
        }

        if ($env == 'local') {
            DB::table('articles')->truncate();
            DB::table('links')->truncate();

            Article::factory()->count(10)->create();
            Link::factory()->count(15)->create();
        }

        if ($env == 'local' || $env == 'staging') {
            DB::table('users')->truncate();

            DB::table('users')->insert([
                [
                    'name' => 'Test Admin',
                    'email' => 'admin@example.com',
                    'email_verified_at' => now(),
                    'role_id' => 1,
                    'remember_token' => Str::random(10),
                    'password' => bcrypt('mypass'), // password
                ],
                [
                    'name' => 'Test User',
                    'email' => 'user@example.com',
                    'email_verified_at' => now(),
                    'role_id' => 2,
                    'remember_token' => Str::random(10),
                    'password' => bcrypt('mypass'), // password
                ]
            ]);
        }
    }
}
