<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->addColumn('integer', 'order')->default(0)->unsigned();
        });

        User::query()->where('id', 4)->update(['order' => 1]);
        User::query()->where('id', 13)->update(['order' => 2]);
        User::query()->where('id', 5)->update(['order' => 3]);
        User::query()->where('id', 18)->update(['order' => 4]);
        User::query()->where('id', 19)->update(['order' => 5]);
        User::query()->where('id', 6)->update(['order' => 6]);
        User::query()->where('id', 20)->update(['order' => 7]);
        User::query()->where('id', 21)->update(['order' => 8]);
        User::query()->where('id', 22)->update(['order' => 9]);
        User::query()->where('id', 23)->update(['order' => 10]);
        User::query()->where('id', 24)->update(['order' => 11]);
        User::query()->where('id', 25)->update(['order' => 12]);
        User::query()->where('id', 26)->update(['order' => 13]);
        User::query()->where('id', 27)->update(['order' => 14]);
        User::query()->where('id', 28)->update(['order' => 15]);
        User::query()->where('id', 7)->update(['order' => 16]);
        User::query()->where('id', 8)->update(['order' => 17]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
