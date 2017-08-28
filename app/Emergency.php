<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
	protected $casts = [
		'lat' => 'float',
		'lng' => 'float',
		'accuracy_score' => 'float'
	];

	public function message() {
		return $this->belongsTo(Message::class);
	}

	protected function serializeDate(\DateTimeInterface $date) {
		return $date->toIso8601String();
	}
}
