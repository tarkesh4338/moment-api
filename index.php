<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//require __DIR__ . '/../vendor/autoload.php';
require 'vendor/autoload.php';
require 'config/db.php';
$db = new DBConnection();
$conn = $db->getConnection();
$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/hello', function (Request $request, Response $response, $args) {
    //$data = array("name"=>"Tarkeshwer","age"=>25,"company"=>"Innoeye");
    $data = file_get_contents("https://type.fit/api/quotes");
    $payload = json_encode($data);
    $arrayData = json_decode($payload, true);
    
    $response->getBody()->write($arrayData);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/quotes', function (Request $request, Response $response, $args) {
    global $conn;
    $quotes = array();
    $quotes['quotes'] = array();
    $stmt = $conn->prepare("SELECT id, text, author FROM QuoteLibrary");
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $quote = array("id"=> $id, "text"=> $text, "author" => $author);
        array_push($quotes['quotes'], $quote);
    }
    $payload = json_encode($quotes);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type','application/json');
});


$app->run();
