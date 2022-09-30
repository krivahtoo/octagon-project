<?php

declare(strict_types=1);

use App\UserAction;
use App\RegisterAction;
use Slim\App;

return function (App $app) {
  $app->group('/api', function (App $app) {
    $app->get('/user/{phone:.+}', UserAction::class);
    $app->post('/register', RegisterAction::class);
  });
};
