<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPersonAndRolIdAtUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
            $table->bigInteger('person_id')->unsigned()->after('id');
            $table->bigInteger('role_id')->unsigned()->after('person_id');
            $table->boolean('condicion')->default(1)->after('password');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
