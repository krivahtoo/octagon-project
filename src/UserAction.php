<?php

namespace App;

use Monolog\Logger;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserAction
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
    $result = [
      'id' => 1,
      'first_name' => 'John',
      'last_name' => 'Doe',
      'phone' => '0712345678',
    ];

    return $response->withJson($result);
  }
}
