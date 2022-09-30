<?php

namespace App;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ErrorHandler
{
  /**
   * Invoked by slim on error.
   *
   * @param ServerRequestInterface $request   Represents the current HTTP request.
   * @param ResponseInterface      $response  Represents the current HTTP response.
   * @param Exception              $exception Exception passed by Slim.
   *
   * @return ResponseInterface
   */
  public function __invoke(Request $request, Response $response, Exception $exception)
  {
    return $response->withJson(['success' => false, 'error' => 'Something went wrong!'], 500);
  }
}
