<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('map');
});

Route::get('emergencies', function () {
	return App\Emergency::getCached();
});

Route::get('emergencies/csv', function (Request $request) {
	ini_set('memory_limit', '4096M');

	$format = $request->input('format', 'full');

	if ($format !== 'full' && $format !== 'spreadsheet') {
		return response()->json(['error' => 'Invalid format. Valid formats are "full" or "spreadsheet"'], 400);
	}

	$headers = [
		'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
		'Content-type' => 'text/csv',
		'Content-Disposition' => 'attachment; filename=emergencies.csv',
		'Expires' => '0',
		'Pragma' => 'public'
    ];

	$rows = App\Emergency::getCached();

	$csvHeaders = $format === 'spreadsheet' ? [
		'incident number',
		'timestamp',
		'name',
		'address',
		'empty',
		'city',
		'Zipcode',
		'empty',
		'latitude',
		'longitude',
		'empty',
		'empty',
		'empty',
		'empty',
		'Twitter message text'
	] : [
		'id',
		'lat',
		'lng',
		'accuracy_score',
		'accuracy_type',
		'street',
		'city',
		'zip',
		'state',
		'is_safe',
		'created_at',
		'updated_at',
		'message.id',
		'message.twitter_id',
		'message.message_created',
		'message.message_text',
		'message.user_id',
		'message.user_handle',
		'message.user_name',
		'message.user_location',
		'message.created_at',
		'message.updated_at'
	];

	$out = fopen('php://output', 'w');
	fputcsv($out, $csvHeaders);

	$callback = function() use ($rows, $out, $format) {
		$rows->each(function ($row) use ($out, $format) {
			fputcsv($out, $format === 'spreadsheet' ? [
				$row->id,
				$row->message->message_created,
				$row->message->user_name,
				$row->street,
				'',
				$row->city,
				$row->zip,
				'',
				$row->lat,
				$row->lng,
				'',
				'',
				'',
				'',
				$row->message->message_text
			] : [
				$row->id,
				$row->lat,
				$row->lng,
				$row->accuracy_score,
				$row->accuracy_type,
				$row->street,
				$row->city,
				$row->zip,
				$row->state,
				$row->is_safe,
				$row->created_at,
				$row->updated_at,
				$row->message->id,
				$row->message->twitter_id,
				$row->message->message_created,
				$row->message->message_text,
				$row->message->user_id,
				$row->message->user_handle,
				$row->message->user_name,
				$row->message->user_location,
				$row->message->created_at,
				$row->message->updated_at
			]);
		});

		fclose($out);
	};

	return response()->stream($callback, 200, $headers);
});