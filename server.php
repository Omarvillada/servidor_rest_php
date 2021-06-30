<?php
/*
-- Autenticacion via HTTP
$user = array_key_exists('PHP_AUTH_USER',$_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';
$pwd = array_key_exists('PHP_AUTH_PW',$_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';

if ($user !== 'omar' || $pwd !== '1234') {
	die;
}
*/

//Autenticacion via HMAC
/*
if (
    !array_key_exists('HTTP_X_HASH', $_SERVER) ||
    !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ||
    !array_key_exists('HTTP_X_UID', $_SERVER)
    
    ) {
        header( 'Status-Code: 403' );
	
		echo json_encode(
			[
				'error' => "No autorizado",
			]
		);
		
		die;
}

list($hash, $uid, $timestamp) = [
    $_SERVER['HTTP_X_HASH'],
    $_SERVER['HTTP_X_UID'],
    $_SERVER['HTTP_X_TIMESTAMP'],
    
];

$secret = 'Sh!! No se lo cuentes a nadie!';

$newHash = sha1($uid.$timestamp.$secret);

if ($newHash != $hash) {
    header( 'Status-Code: 403' );
	
    echo json_encode(
        [
            'error' => "No autorizado. Hash esperado: $newHash, hash recibido: $hash",
        ]
    );
    
    die;
}
*/

// Autenticacion via Access Token
/*
if (!array_key_exists('HTTP_X_TOKEN', $_SERVER)) {
    die;
}

$url = 'http://localhost:8001';

$ch = curl_init($url);

curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,[
        "X-Token: {$_SERVER['HTTP_X_TOKEN']}"
    ]
    );

curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);

$ret = curl_exec($ch);

if ($ret !== 'true') {
    die;
}
*/
header('Content-Type: application/json');
header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

// Definimos los recursos disponibles
$allowedResourceType = [
    'books',
    'authors',
    'generes',
];

//Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];

if (!in_array($resourceType, $allowedResourceType)) {
    http_response_code( 400 );

    die;
}

//Defino los recursos

$books = [
    1 => [
        'titulo' => 'Del inconveniente de haber nacido',
        'id_autor' => 2,
        'id_genero' => 2,
    ],
    2 => [
        'titulo' => 'Viaje a pie',
        'id_autor' => 3,
        'id_genero' => 1,
    ],
    3 => [
        'titulo' => 'La Iliada',
        'id_autor' => 5,
        'id_genero' => 1,
    ]
];


//Levantamos el id del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

//Generamos la respuesta asumiendo que el pedido es correcto
switch (strtoupper($_SERVER['REQUEST_METHOD'])){
    case 'GET':
        if (empty($resourceId)) {
            echo json_encode($books);    
        }else{
            if (array_key_exists($resourceId, $books)) {
                echo json_encode($books[$resourceId]);
            }else{
                http_response_code(404);
            }
        }
        
        break;
    case 'POST':
        $json = file_get_contents('php://input');

        $books[] = json_decode($json, true);

        //echo array_keys($books)[count($books) - 1];
        echo json_encode($books);
        break;
    case 'PUT':
        //Validamos que el recurso buscado exista
        if (!empty($resourceId) && array_key_exists($resourceId, $books)) {
            // Tomamos la entrada cruda
            $json = file_get_contents('php://input');

            //Reescribimos el json recibido
            $books[$resourceId] = json_decode($json, true);

            // Retornamos la coleccion modificada en formato json

            echo json_encode($books);

        }
        break;
    case 'DELETE':
        //Validamos que el recurso exista
        if (!empty($resourceId) && array_key_exists($resourceId, $books)) {
            //Eliminamos el recurso
            unset($books[$resourceId]);
        }
        echo json_encode($books);
        
}