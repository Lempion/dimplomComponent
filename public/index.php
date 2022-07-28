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
    },

    \Aura\SqlQuery\QueryFactory::class => function () {
        return new \Aura\SqlQuery\QueryFactory('mysql');
    },
]);

$container = $containerBuilder->build();
// DI end

// Route
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'main']);

    $r->addRoute('GET', '/register', ['App\controllers\HomeController', 'register']);

    $r->addRoute('GET', '/login', ['App\controllers\HomeController', 'login']);

    $r->addRoute('GET', '/profile/{id:\d+}', ['App\controllers\HomeController', 'profile']);

    $r->addRoute('GET', '/status/{id:\d+}', ['App\controllers\HomeController', 'status']);

    $r->addRoute('GET', '/user/{id:\d+}', ['App\controllers\HomeController', 'user']);

    $r->addRoute('GET', '/media/{id:\d+}', ['App\controllers\HomeController', 'media']);

    $r->addRoute('GET', '/security/{id:\d+}', ['App\controllers\HomeController', 'security']);

    $r->addRoute('GET', '/create_user', ['App\controllers\HomeController', 'createUser']);

    $r->addRoute('POST', '/reg', ['App\controllers\AccountController', 'register']);

    $r->addRoute('POST', '/log', ['App\controllers\AccountController', 'login']);

    $r->addRoute('GET', '/logout', ['App\controllers\AccountController', 'logout']);

    $r->addRoute('POST', '/update_data/{changeId:\d+}', ['App\controllers\AccountController', 'updateData']);

    $r->addRoute('POST', '/update_status/{changeId:\d+}', ['App\controllers\AccountController', 'updateStatus']);

    $r->addRoute('POST', '/update_media/{changeId:\d+}', ['App\controllers\AccountController', 'updateMedia']);

    $r->addRoute('POST', '/update_security/{changeId:\d+}', ['App\controllers\AccountController', 'updateSecurity']);

    $r->addRoute('POST', '/add_user', ['App\controllers\AccountController', 'addUser']);

    $r->addRoute('GET', '/remove_user/{id:\d+}', ['App\controllers\AccountController', 'removeUser']);

    $r->addRoute('GET', '/verif_email/', ['App\controllers\AccountController', 'verificationEmail']);

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
