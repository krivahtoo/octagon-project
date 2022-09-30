<?php

declare(strict_types=1);

use App\UserAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
  // Add route callbacks
  $app->get('/', function (Request $request, Response $response, $args) {
    return $response->withStatus(200)->write('Hello World!');
  });

  $app->get('/user/{phone}', UserAction::class);
};
