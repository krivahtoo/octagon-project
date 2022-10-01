<?php

namespace App\Auth;

use App\RequestBridge;
use App\ResponseBridge;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\message\ResponseInterface;
use OAuth2;

/**
 * The revoke class.
 */
class Revoke
{
  const ROUTE = '/revoke';

  /**
   * The oauth2 server instance.
   *
   * @var OAuth2\Server
   */
  private $server;

  /**
   * Construct a new instance of Revoke.
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
   * @return ResponseInterface
   */
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $arguments = [])
  {
    $response = ResponseBridge::fromOAuth2(
      $this->server->handleRevokeRequest(
        RequestBridge::toOAuth2($request)
      )
    );
    return $response->withHeader('Content-Type', 'application/json');
  }
}
