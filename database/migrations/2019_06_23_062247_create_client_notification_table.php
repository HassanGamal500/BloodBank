<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientNotificationTable extends Migration {

	public function up()
	{
		Schema::create('client_notification', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('client_id');
			$table->integer('notification_id');
			$table->tinyInteger('is_read');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('client_notification');
	}
}