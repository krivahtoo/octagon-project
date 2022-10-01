<?php

declare(strict_types=1);

use App\Auth\Revoke;
use App\Auth\Token;
use App\Authorization;
use App\UserAction;
use App\RegisterAction;
use OAuth2\Server;
use Slim\App;

return function (App $app, Server $server) {
  $app->group('/api', function (App $app) use ($server) {
    $auth = new Authorization($server, $app->getContainer());
    $app->get('/user', new UserAction($app->getContainer(), $server))->add($auth);
    $app->post('/token', new Token($server))->setName('token');
    $app->post('/revoke', new Revoke($server))->setName('revoke');
    $app->post('/register', RegisterAction::class);
  });
};
