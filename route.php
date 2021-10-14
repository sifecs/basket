<?php
require  $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use DI\ContainerBuilder;
use League\Plates\Engine;

$containerBuilder = new ContainerBuilder;

$containerBuilder->addDefinitions([
    PDO::class => function() {
        return new PDO("mysql:host=localhost;dbname=grovawe","root","root");
    }
]);
$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['src\Controllers\ProductController', 'index']);
    $r->addRoute('POST', '/product/{id:\d+}', ['src\Controllers\ProductController', 'destroy']);
    $r->addRoute('GET', '/basket', ['src\Controllers\BasketController', 'index']);
    $r->addRoute('POST', '/basket/store', ['src\Controllers\BasketController', 'store']);
    $r->addRoute('POST', '/basket/{id:\d+}', ['src\Controllers\BasketController', 'destroy']);
    $r->addRoute('POST', '/basket/countAjax', ['src\Controllers\BasketController', 'countAjax']);
});
// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        var_dump(404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        var_dump(405);
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($handler, $vars);
        break;
}
?>