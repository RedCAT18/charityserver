<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userdata', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id',10)->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('organization_name');
            $table->string('business_number');
            $table->string('username');
            $table->string('nickname')->unique();
            $table->string('phone');
            $table->string('mobile');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('postcode');
            $table->boolean('webzine');
            $table->string('stamp');
            $table->string('logo');
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
        Schema::drop('userdata');

    }
}
