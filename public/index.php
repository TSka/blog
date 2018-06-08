<?php

use App\Repository\ArticleNotFoundException;
use Framework\Container\Container;
use Framework\Container\ContainerInterface;
use Framework\Http\JsonResponse;
use Framework\Http\Request;
use Framework\Http\RequestFactory;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use App\Http\Controllers;
use Framework\Http\Router\SimpleRouter;
use Framework\Http\RouteResolver;

chdir(dirname(__DIR__));
require __DIR__ . '/../vendor/autoload.php';

$container = Container::getInstance();
$container->set('config', [
    'db' => [
        'host' => '127.0.0.1',
        'db' => 'blog',
        'user' => 'user',
        'password' => '',
        'charset' => 'utf8',
    ],
]);
$container->set(SimpleRouter::class, function (ContainerInterface $container) {
    /** @var RouteCollection $routes */
    $routes = $container->get(RouteCollection::class);

    $routes->get('home', '/', [Controllers\HomeController::class, 'index']);
    $routes->get(
        'articles.list',
        '/articles',
        [Controllers\ArticlesController::class, 'list']
    );
    $routes->get(
        'articles.show',
        '/articles/{id}',
        [Controllers\ArticlesController::class, 'show'],
        ['id' => '\d+']
    );
    $routes->post(
        'articles.store',
        '/articles',
        [Controllers\ArticlesController::class, 'store'],
        ['id' => '\d+']
    );
    $routes->post(
        'articles.update',
        '/articles/{id}',
        [Controllers\ArticlesController::class, 'update'],
        ['id' => '\d+']
    );
    $routes->delete(
        'articles.delete',
        '/articles/{id}',
        [Controllers\ArticlesController::class, 'delete'],
        ['id' => '\d+']
    );

    return new SimpleRouter($routes);
});
$container->set(Request::class, function () {
    return RequestFactory::fromGlobals();
});
$container->set(PDO::class, function (ContainerInterface $container) {
    $dbConfig = $container->get('config')['db'];
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    return new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['db']};charset={$dbConfig['charset']}",
        $dbConfig['user'],
        $dbConfig['password'],
        $opt
    );
});

/** @var SimpleRouter $router */
$router = $container->get(SimpleRouter::class);

/** @var RouteResolver $resolver */
$resolver = $container->get(RouteResolver::class);

/** @var Request $request */
$request = $container->get(Request::class);

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $resolver->resolve($result->getHandler());
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Page not found'], 404);
} catch (ArticleNotFoundException $e) {
    $response = new JsonResponse(['error' => 'Page not found'], 404);
}

header('HTTP/1.0 '. $response->getStatusCode() . ' ' . $response->getReasonPhrase());
foreach ($response->getHeaders() as $header => $value) {
    header($header.':'.$value);
}
echo $response->getBody();