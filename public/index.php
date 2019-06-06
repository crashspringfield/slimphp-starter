<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

use Respect\Validation\Validator as v;

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Register middleware
// $middleware = require __DIR__ . '/../src/middleware.php';
// $middleware($app);

// Register routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$container = $app->getContainer();

// Set up DB w/ Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){
  return $capsule;
};

// Set up validation
$container['validator'] = function($container) {
  return new \App\Validation\Validator;
};

// Set up Authentication
$container['auth'] = function($container) {
  return new \App\Auth\Auth;
};

// Set up CSRF protection
$container['csrf'] = function($container) {
  return new \Slim\Csrf\Guard;
};

// Set up Middleware
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

// Set up Controllers
$container['HomeController'] = function($container) {
  return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container) {
  return new \App\Controllers\Auth\AuthController($container);
};

v::with('App\\Validation\\Rules');

// Set up Twig templates
$container['view'] = function($container) {
  $view = new \Slim\Views\Twig(__DIR__ . '/../src/resources/views', [
    'cache' => false, // dev
  ]);

  $view->addExtension(new \Slim\Views\TwigExtension(
    $container->router,
    $container->request->getUri()
  ));

  $view->getEnvironment()->addGlobal('errors', []);

  $view->getEnvironment()->addGlobal('auth', [
    'check' => $container->auth->check(),
    'user' => $container->auth->user()
  ]);

  return $view;
};

// Run app
$app->run();
