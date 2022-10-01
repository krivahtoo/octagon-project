<?php

namespace App\Auth;

use App\RequestBridge;
use App\ResponseBridge;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use OAuth2;
use OAuth2\Response;

/**
 * Slim route for /token endpoint.
 */
class Token
{
  const ROUTE = '/token';

  /**
   * The OAuth2 server instance.
   *
   * @var OAuth2\Server
   */
  private $server;

  /**
   * Create a new instance of the Token route.
   *
   * @param OAuth2\Server $server The oauth2 server imstance.
   */
  public function __construct(OAuth2\Server $server)
  {
    $this->server = $server;
  }

  /**
   * Invoke this route callback.
   *
   * @param ServerRequestInterface $request   Represents the current HTTP request.
   * @param ResponseInterface      $response  Represents the current HTTP response.
   * @param array                  $arguments Values for the current route’s named placeholders.
   *
   * @return RequestInterface
   */
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $arguments = [])
  {
    $response = new Response();
    $this->server->grantAccessToken(RequestBridge::toOAuth2($request), $response);

    $response = ResponseBridge::fromOauth2($response);

    if ($response->hasHeader('Content-Type')) {
      return $response;
    }

    return $response->withHeader('Content-Type', 'application/json');
  }
}
