<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PopArchive extends Model
{
	protected $fillable = [
		'result', 'pop_id'
	];

	protected $casts = [
		'result' => 'array', 'data' => 'array', 'innormal' => 'array'
	];
	public function pop()
	{
		return $this->belongsTo(Pop::class);
	}
}
