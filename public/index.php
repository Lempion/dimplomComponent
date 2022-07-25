<?php

if (!session_id()) @session_start();

require '../vendor/autoload.php';

use DI\ContainerBuilder;
// DI
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([

    PDO::class => function () {
        $db = 'mysql';
        $host = 'localhost';
        $dbName = 'appcomponents';
        $login = 'root';
        $pass = '';

        return new PDO("{$db}:host={$host};dbname={$dbName}", $login, $pass);
    }
]);

$container = $containerBuilder->build();
// DI end

// Route
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'main']);

    $r->addRoute('GET', '/register', ['App\controllers\HomeController', 'register']);

    $r->addRoute('GET', '/login', ['App\controllers\HomeController', 'login']);

    $r->addRoute('GET', '/admin/create_user', ['App\controllers\HomeController', 'adminAddUser']);

    $r->addRoute('GET', '/admin/change_status', ['App\controllers\HomeController', 'adminChangeStatus']);

//    $r->addRoute('GET', '/admin/add_user', ['App\controllers\AccountController', 'createUser']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $container->call($handler, $vars);
        break;
}
// Route end
