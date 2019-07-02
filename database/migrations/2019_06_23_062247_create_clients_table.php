<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email');
			$table->date('birth_of_date');
			$table->string('phone', 255);
			$table->string('password');
			$table->integer('blood_type_id');
			$table->integer('city_id');
			$table->string('pin_code');
            $table->string('api_token', 255)->unique()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}