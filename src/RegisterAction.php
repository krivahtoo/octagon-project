<?php

namespace App;

use App\Auth\Storage;
use Monolog\Logger;
use PDO;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class RegisterAction
{
  protected PDO $db;
  protected Logger $logger;

  /**
   * Constructor receives container instance
   *
   * @param ContainerInterface   $c  container instance
   */
  public function __construct(ContainerInterface $c)
  {
    $this->db = $c->get('db');
    $this->logger = $c->get('logger');
  }

  /**
   * Handle POST /register requests.
   *
   * @param ServerRequestInterface $request   Represents the current HTTP request.
   * @param ResponseInterface      $response  Represents the current HTTP response.
   * @param array                  $arguments Values for the current route’s named placeholders.
   *
   * @return ResponseInterface
   */
  public function __invoke(Request $request, Response $response, array $args = [])
  {
    $this->logger->debug("User registration request");

    $storage = new Storage($this->db);
    $data = $request->getParsedBody();

    if ($storage->setUser($data['phone'], $data['password'], $data['first_name'], $data['last_name'])) {
      return $response->withJson(['success' => true], 201);
    } else {
      return $response->withJson(['success' => false], 500);
    }
  }
}
