<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auth\Storage;
use App\ErrorHandler;
use OAuth2\GrantType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\App;
use PDO;
use Slim\Http\Request;
use Slim\Http\Response;

$settings = require __DIR__ . '/../app/settings.php';
$routes = require __DIR__ . '/../app/routes.php';

// Create Slim app
$app = new App(['settings' => $settings]);

$pdo = new PDO('sqlite:' . $settings['db']['path']);

// setup OAuth2 storage
$storage = new Storage($pdo);

// Fetch DI Container
$container = $app->getContainer();

// Initialize monolog
$container['logger'] = function ($c) {
  $log = $c['settings']['logger'];
  $logger = new Logger($log['name']);
  $file_handler = new StreamHandler($log['path']);
  $logger->pushHandler($file_handler);
  return $logger;
};

// setup error handler
$container['errorHandler'] = function ($c) {
  return new ErrorHandler();
};

// Initialize database PDO instance
$container['db'] = function ($c) {
  $db = $c['settings']['db'];
  $pdo = new PDO('sqlite:' . $db['path']);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  return $pdo;
};

$server = new OAuth2\Server(
  $storage,
  [
    'access_lifetime'                => 3600,
    'always_issue_new_refresh_token' => true,
    'refresh_token_lifetime'         => 2419200,
  ],
  [
    new GrantType\RefreshToken($storage),
    new GrantType\UserCredentials($storage),
  ]
);

$routes($app, $server);

// Override the default Not Found Handler
// This should return index.html for the frontend to handle the routing
unset($app->getContainer()['notFoundHandler']);
$app->getContainer()['notFoundHandler'] = function ($c) {
  return function (Request $request, Response $response) use ($c) {
    $c->logger->debug('404 error');
    if ($request->isGet()) {
      return $response
        ->withHeader('Content-Type', 'text/html')
        ->write(file_get_contents(__DIR__ . '/index.html'));
    } else {
      return $response
        ->withJson(['error' => 'Not found'], 404);
    }
  };
};

$app->run();
