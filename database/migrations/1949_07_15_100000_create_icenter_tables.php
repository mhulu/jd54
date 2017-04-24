<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIcenterTables extends Migration {
	/**
	 * icenter表结构，涵盖了基础功能
	 * 日后扩展可以用apps添加
	 * 1. users 用户表
	 * 2. profiles 用户详细资料表
	 * 3. units 部门表
	 * @return void
	 */
	public function up() {

		Schema::create('units', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('owner_id')->unsigned()->index()->nullable();
			$table->string('name', 60);
			$table->timestamps();
		});

		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 12)->nullable();
			$table->string('mobile', 12)->unique();
			$table->string('email')->nullable();
			$table->string('im_token')->comment('RongCloud Token')->nullable();
			$table->string('weixin', 66)->unique();
			$table->string('password');
			$table->tinyInteger('status')->default(1);
			$table->rememberToken();
			$table->integer('unit_id')->unsigned()->default(1);
			$table->timestamp('last_login')->nullable();
			$table->string('last_ip', 45)->nullable();
			$table->timestamps();

			$table->foreign('unit_id')
				->references('id')
				->on('units')
				->onDelete('cascade');
		});

		Schema::create('profiles', function (Blueprint $table) {
			$table->increments('id');
			$table->string('nickname', 12)->default('未名')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('avatar')->default('http://static.stario.net/images/avatar.png');
			$table->boolean('sex')->default(0);
			$table->string('qq', 15)->nullable();
			$table->string('wechat')->nullable();
			$table->string('birthplace', 30)->nullable();
			$table->date('birthday')->default('1977-7-15');
			$table->timestamps();
			$table->softDeletes();
		});

		// Schema::create('followers', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->unsignedInteger('user_id');
		//     $table->unsignedInteger('follow_id');
		//     $table->timestamps();
		// });

		// Schema::create('articles', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->integer('category_id')->unsigned();
		//     $table->integer('user_id')->unsigned();
		//     $table->integer('last_user_id')->unsigned();
		//     $table->string('slug')->unique();
		//     $table->string('title');
		//     $table->string('subtitle');
		//     $table->text('content');
		//     $table->string('page_image')->nullable();
		//     $table->string('meta_description')->nullable();
		//     $table->boolean('is_original')->default(false);
		//     $table->boolean('is_draft')->default(false);
		//     $table->integer('view_count')->unsigned()->default(0)->index();
		//     $table->timestamp('published_at')->nullable();
		//     $table->timestamps();
		//     $table->softDeletes();
		// });

		// Schema::create('tags', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->string('tag')->unique();
		//     $table->string('title');
		//     $table->string('meta_description');
		//     $table->timestamps();
		//     $table->softDeletes();
		// });

		// Schema::create('visitors', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->integer('article_id')->unsigned();
		//     $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
		//     $table->string('ip', 32);
		//     $table->string('country')->nullable();
		//     $table->integer('clicks')->unsigned()->default(1);
		//     $table->timestamps();
		// });

		// Schema::create('categories', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->tinyInteger('parent_id')->unsigned()->default(0);
		//     $table->string('name');
		//     $table->string('path');
		//     $table->string('description')->nullable();
		//     $table->string('image_url')->nullable();
		//     $table->timestamps();
		// });

		// Schema::create('discussions', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->integer('user_id')->unsigned();
		//     $table->integer('last_user_id')->unsigned();
		//     $table->string('title');
		//     $table->text('content');
		//     $table->tinyInteger('status')->default(false);
		//     $table->timestamps();
		//     $table->softDeletes();
		// });

		// Schema::create('comments', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->integer('user_id')->unsigned();
		//     $table->integer('commentable_id')->unsigned();
		//     $table->char('commentable_type');
		//     $table->text('content');
		//     $table->timestamps();
		//     $table->softDeletes();
		// });

		// Schema::create('links', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->string('name')->index();
		//     $table->string('link')->index();
		//     $table->text('image')->nullable();
		//     $table->boolean('status')->default(true);
		//     $table->timestamps();
		//     $table->softDeletes();
		// });

		// Schema::create('taggables', function (Blueprint $table) {
		//     $table->integer('tag_id')->unsigned()->index();
		//     $table->integer('taggable_id')->unsigned()->index();
		//     $table->string('taggable_type')->index();
		// });

		// Schema::create('events', function (Blueprint $table) {
		//     $table->integer('id')->unsigned()->index();
		//     $table->string('type');
		//     $table->morphs('events');
		//     $table->text('data');
		//     $table->timestamps();
		// });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::dropIfExists('users');
		Schema::dropIfExists('units');
		Schema::dropIfExists('profiles');
		// Schema::dropIfExists('articles');
		// Schema::dropIfExists('tags');
		// Schema::dropIfExists('categories');
		// Schema::dropIfExists('discussions');
		// Schema::dropIfExists('comments');
		// Schema::dropIfExists('links');
		// Schema::dropIfExists('taggables');
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}
}
