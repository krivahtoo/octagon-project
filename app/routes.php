<?php

declare(strict_types=1);

use App\Auth\Token;
use App\Authorization;
use App\UserAction;
use App\RegisterAction;
use OAuth2\Server;
use Slim\App;

return function (App $app, Server $server) {
  $auth = new Authorization($server, $app->getContainer());
  $app->post('/api/token', new Token($server))->setName('token');
  $app->group('/api', function (App $app) use ($auth) {
    $app->get('/user/{phone:.+}', UserAction::class)->add($auth);
    $app->post('/register', RegisterAction::class);
  });
};
