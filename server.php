<?php

$count = 0;

if ( !is_dir('./storage') ) mkdir('./storage');

$data = ( !file_exists('database.json') ? [] : json_decode( file_get_contents('database.json') ) );

$writable = fopen('database.json', 'w');

foreach ($_FILES as $key => $file) {

	if ( !move_uploaded_file($file['tmp_name'], './storage/' . $file['name']) ) {
		return print_r( json_encode(['message' => 'No fue posible subir los archivos', 'status' => http_response_code(500)] ));
	}

	array_push($data, [
		'id' => $key,
		'file_name' => $file['name']
	]);

	$count++;

}

if ( $count == count( $_FILES ) ) {
	fwrite($writable, json_encode($data));
	fclose($writable);

	return print_r(json_encode(
		[
			'messsage' => 'Se subieron ' . $count . ' fotos con éxito',
			'status' => http_response_code(200),
			$data
		]
	));
}