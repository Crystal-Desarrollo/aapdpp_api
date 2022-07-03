<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("phone", 15);
            $table->string("address", 100);
            $table->string("additional_info", 512);
            $table->boolean("membership_status")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("phone");
            $table->dropColumn("address");
            $table->dropColumn("additional_info");
            $table->dropColumn("membership_status");
        });
    }
};
