<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Link;
use App\Models\Meeting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        if (DB::table('roles')->count() == 0) {
            DB::table('roles')->insert([['name' => 'admin'], ['name' => 'member']]);
        }

        if ($env == 'local') {
            DB::table('articles')->truncate();
            DB::table('links')->truncate();

            Article::factory()->count(10)->create();
            Link::factory()->count(15)->create();
            Meeting::factory()->count(10)->create();
        }

        if ($env == 'local' || $env == 'staging') {
            DB::table('users')->truncate();

            $adminRole = Role::where('name', 'admin')->first();
            $memberRole = Role::where('name', 'member')->first();

            DB::table('users')->insert([
                [
                    'name' => 'Test Admin',
                    'email' => 'admin@example.com',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'active' => true,
                    'role_id' => $adminRole->id,
                    'remember_token' => Str::random(10),
                    'password' => bcrypt('mypass'), // password
                ],
                [
                    'name' => 'Test User',
                    'email' => 'user@example.com',
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'active' => true,
                    'role_id' => $memberRole->id,
                    'remember_token' => Str::random(10),
                    'password' => bcrypt('mypass'), // password
                ]
            ]);
        }

        if ($env == 'production') {
            $adminRole = Role::where('name', 'admin')->first();
            $adminExists = DB::table('users')->where('role_id', '=', $adminRole->id)->count() !== 0;

            if (!$adminExists) {
                DB::table('users')->insert([
                    [
                        'name' => 'Administrador',
                        'email' => env('DEFAULT_ADMIN_EMAIL'),
                        'email_verified_at' => now(),
                        'created_at' => now(),
                        'active' => true,
                        'role_id' => $adminRole->id,
                        'remember_token' => Str::random(10),
                        'password' => bcrypt(env('DEFAULT_ADMIN_PASS')), // password
                    ]
                ]);
            }
        }
    }
}
