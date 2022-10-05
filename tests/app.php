<?php
// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auth\Storage;
use App\ErrorHandler;
use OAuth2\GrantType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\App;

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

return $app;
