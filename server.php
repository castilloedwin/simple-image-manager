<?php

$count = 0;

if ( !is_dir('./storage') ) mkdir('./storage');

$data = ( !file_exists('database.json') ? [] : json_decode( file_get_contents('database.json') ) );

foreach ($_FILES as $key => $file) {

	$path_info = pathinfo( './storage/' . $file['name'] );
	$photo_name = str_random($path_info);

	if ( !move_uploaded_file($file['tmp_name'], './storage/' . $photo_name) ) {
		return print_r( json_encode(['message' => 'No fue posible subir los archivos', 'status' => http_response_code(500)] ));
	}

	array_push($data, [ 'id' => $key, 'file_name' => $photo_name ]);

	$count++;

}

function str_random ($path_info) {
	$string = 'AaBbCcDdEeFfGgHhIiJjKkLlMm0123456789_';
	return str_shuffle($string) . '.' . $path_info['extension'];
}

if ( $count == count( $_FILES ) ) {

	$writable = fopen('database.json', 'w');
	fwrite($writable, json_encode($data));
	fclose($writable);

	$message = ( $count > 1 ? 'Se subieron ' . $count . ' fotos con éxito' : 'Se subió ' . $count . ' foto con éxito' );

	return print_r(json_encode(
		[
			'message' => $message,
			'status' => http_response_code(200),
			$data
		]
	));
}