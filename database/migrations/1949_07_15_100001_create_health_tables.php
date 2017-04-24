<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHealthTables extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('pops', function (Blueprint $table) {
			$table->increments('id');
			$table->string('identify', 20)->unique();
			$table->string('password', 66);
			$table->string('name', 10);
			$table->string('paytype', 20);
			$table->tinyInteger('sex')->unsigned();
			$table->string('phone', 20);
			$table->string('weixin', 66)->unique();
			$table->string('address')->nullable();
			$table->boolean('livetype')->default(false);
			$table->string('nation', 15)->nullable();
			$table->date('birthday', 12);
			$table->string('birthplace', 30)->nullable();
			$table->text('memo')->nullable();
			$table->timestamps();
		});
		Schema::create('pop_archives', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('pop_id')->unsigned()->nullable();
			$table->string('check_unit', 32);
			$table->string('doctor', 10);
			$table->json('data');
			$table->json('result')->nullable();
			$table->json('innormal')->nullable();
			$table->timestamps();
			$table->foreign('pop_id')
				->references('id')
				->on('pops')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->index(['created_at', 'check_unit']);
		});
		Schema::create('geriatrics', function (Blueprint $table) {
			$table->increments('id');
			$table->string('identify', 20)->unique();
			$table->string('password', 66);
			$table->string('name', 10);
			$table->string('sex', 10);
			$table->string('phone', 20);
			$table->string('weixin', 66)->unique();
			$table->string('address');
			$table->date('birthday', 12);
			$table->string('check_unit', 32);
			$table->date('check_date');
			$table->string('doctor', 10);
			$table->text('memo')->nullable();
			$table->timestamps();
		});
		Schema::create('geriatric_archives', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('pop_id')->unsigned();
			$table->json('result');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::dropIfExists('pops');
		Schema::dropIfExists('pop_archives');
		Schema::dropIfExists('geriatrics');
		Schema::dropIfExists('geriatric_archives');
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}
}
