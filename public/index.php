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
    },

    \League\Plates\Engine::class => function () {
        return new \League\Plates\Engine('../app/views');
    },

    \Delight\Auth\Auth::class => function ($container) {
        return new \Delight\Auth\Auth($container->get('PDO'));
    }
]);

$container = $containerBuilder->build();
// DI end

// Route
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'main']);

    $r->addRoute('GET', '/register', ['App\controllers\HomeController', 'register']);

    $r->addRoute('GET', '/login', ['App\controllers\HomeController', 'login']);

    $r->addRoute('GET', '/profile', ['App\controllers\HomeController', 'profile']);

    $r->addRoute('GET', '/admin/status/{id:\d+}', ['App\controllers\HomeController', 'status']);

    $r->addRoute('GET', '/status', ['App\controllers\HomeController', 'status']);

    $r->addRoute('GET', '/admin/user/{id:\d+}', ['App\controllers\HomeController', 'user']);

    $r->addRoute('GET', '/user', ['App\controllers\HomeController', 'user']);

    $r->addRoute('GET', '/admin/media/{id:\d+}', ['App\controllers\HomeController', 'media']);

    $r->addRoute('GET', '/media', ['App\controllers\HomeController', 'media']);

    $r->addRoute('GET', '/admin/security/{id:\d+}', ['App\controllers\HomeController', 'security']);

    $r->addRoute('GET', '/security', ['App\controllers\HomeController', 'security']);

    $r->addRoute('GET', '/admin/create_user', ['App\controllers\HomeController', 'createUser']);

    $r->addRoute('POST', '/reg', ['App\controllers\AccountController', 'register']);

    $r->addRoute('POST', '/log', ['App\controllers\AccountController', 'login']);

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
