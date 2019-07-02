<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->string('phone');
			$table->string('email');
			$table->text('about_app');
			$table->string('facebook_link');
			$table->string('twitter_link');
			$table->string('youtube_link');
			$table->string('instagram_link');
			$table->string('whatsapp_link');
			$table->string('google_link');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}