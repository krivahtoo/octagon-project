<?php

namespace App;

use App\Auth\Storage;
use Monolog\Logger;
use OAuth2\Server;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserAction
{
  protected Storage $storage;
  protected Logger $logger;
  protected Server $server;

  /**
   * Constructor receives container instance
   *
   * @param ContainerInterface   $c  container instance
   */
  public function __construct(ContainerInterface $c, Server $server)
  {
    $this->storage = new Storage($c->get('db'));
    $this->logger = $c->get('logger');
    $this->server = $server;
  }

  /**
   * Handle GET /user requests.
   *
   * @param ServerRequestInterface $request   Represents the current HTTP request.
   * @param ResponseInterface      $response  Represents the current HTTP response.
   * @param array                  $arguments Values for the current route’s named placeholders.
   *
   * @return ResponseInterface
   */
  public function __invoke(Request $request, Response $response, array $args = [])
  {
    $this->logger->debug("User request");
    $result = $this->storage->getUser($this->server->getAccessTokenData(\OAuth2\Request::createFromGlobals())['user_id']);

    // we do not want to sent the password
    unset($result['password']);

    return $response->withJson($result);
  }
}
