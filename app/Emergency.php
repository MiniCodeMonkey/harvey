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

	public static function getCached() {
		return cache()->remember('emergencies', 5, function () {
			return static::with('message')->orderBy('created_at', 'DESC')->get();
		});
	}
}
