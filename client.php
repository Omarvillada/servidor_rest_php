<?php
//  Se indica que la petición viene por la termina
$ch = curl_init( $argv[1]);
// Se confifura curl para recibir la respuesta al
// ejecutar la petición
curl_setopt(
	$ch,
	CURLOPT_RETURNTRANSFER,
	true
);
//Realiza la peteción recibida por terminal
$response = curl_exec($ch);
// se optine la respuesta del servidor
$httpCode = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);
// estructura para interpretar las respuestas http
switch ($httpCode){
	case 200:
		echo 'Todo bien!';
		break;
	case 400:
		echo 'Pedido incorrecto';
		break;
	case 404:
		echo 'Recurso no encontrado';
		break;
	case 500:
		echo 'El servidor fallo';
		break;
}