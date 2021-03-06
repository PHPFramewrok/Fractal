<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentViewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content_views', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('content_id');
			$table->string('content_type', 72);
			$table->string('ip_address', 64);
			$table->string('user_agent');
			$table->integer('views')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('content_views');
	}

}