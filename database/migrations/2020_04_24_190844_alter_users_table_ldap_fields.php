<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableLdapFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['password']);
            $table->string('username')->nullable();
            $table->string('cargo')->nullable();
            $table->string('lotacao')->nullable();
            $table->string('ramal')->nullable();
            $table->string('empresa')->nullable();
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

            $table->dropColumn(['username', 'cargo', 'lotacao','ramal','empresa']);
            $table->string('password')->nullable();
        });
    }
}
