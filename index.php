<?php

require_once "Book.php";
require_once "Author.php";

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$route = explode('/', trim($uri, '/'));

if ($method == 'GET')
    $request = $_GET;
else
{
    $rawRequest = file_get_contents('php://input');

    if (substr_count($_SERVER["CONTENT_TYPE"], "application/json"))
        $request = json_decode($rawRequest, true);
    else if (!is_array($rawRequest))
        parse_str($rawRequest, $request);
}


if (count($route) > 2 && !isset($request['id'])) {
    $request['id'] = $route[2];
}

$entityClass = ucfirst($route[1]);

$entity = new $entityClass();

// move to api.php

$jsonResult = null;

switch ($method)
{
    case 'GET':
        // show one item
        if (isset($request['id'])) {
            if ($entity = $entityClass::show($request['id'])) {
                $jsonResult = $entity->serialize();
            } else {
                http_response_code(404);
                $jsonResult = ['error' => 'Nothing found'];
            }
        } else { // show list
            $jsonResult = $entityClass::list();
        }
        break;

    case 'POST':
        $entity->updateFromArray($request);
        $entity->save();

        http_response_code(201);
        break;

    case 'PUT':
        $entity = $entityClass::show($request['id']);
        $entity->updateFromArray($request);
        break;

    case 'DELETE':
        $entity = $entity::show($request['id']);
        $entity->delete();
        break;

    default:
        // I'm a teapot
}

echo json_encode($jsonResult);