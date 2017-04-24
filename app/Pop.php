<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Pop extends Model {
	protected $guarded = ['id'];
	protected $hidden = ['password'];

	public function popArchives() {
		return $this->hasMany(PopArchive::class);
	}

	public function setPasswordAttribute($password) {
		$this->attributes['password'] = bcrypt($password);
	}

	public function setAgeAttribute($birthday) {
		$this->attributes['age'] = Carbon::parse($birthday)->age;
	}

}