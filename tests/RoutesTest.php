<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

final class RoutesTest extends TestCase
{
  // Simulates queries to our REST API using mock environment
  function apiTest($method, $endpoint, $token = false, $postData = [])
  {
    $envOptions = [
      'REQUEST_METHOD' => strtoupper($method),
      'REQUEST_URI' => $endpoint
    ];
    if ($postData) {
      $envOptions['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
    }
    //Authorization: Bearer 
    $env = Environment::mock($envOptions);
    if ($token) {
      $request = $postData ? Request::createFromEnvironment($env)->withHeader('Authorization', 'Bearer ' . $token)->withParsedBody($postData) : Request::createFromEnvironment($env)->withHeader('Authorization', 'Bearer ' . $token);
    } else {
      $request = $postData ? Request::createFromEnvironment($env)->withParsedBody($postData) : Request::createFromEnvironment($env);
    }
    $app = require(__DIR__ . '/app.php');
    $app->getContainer()['request'] = $request;
    $response = $app->run(true);
    return [
      'code' => $response->getStatusCode(),
      'data' => json_decode($response->getBody()->getContents(), true)
    ];
  }

  /**
   * @test
   */
  public function testToken1()
  {
    $data = $this->apiTest('post', '/api/token');
    $this->assertSame($data['code'], 400);
    $this->assertSame($data['data']['error_description'], 'The grant type was not specified in the request');
  }

  /**
   * @test
   */
  public function testToken2()
  {
    $data = $this->apiTest('post', '/api/token', false, ['grant_type' => 'password']);
    $this->assertSame($data['code'], 400);
    $this->assertSame($data['data']['error_description'], 'Client credentials were not found in the headers or body');
  }

  /**
   * @test
   */
  public function testToken3()
  {
    $postData = [
      'grant_type' => 'password',
      'client_id' => 1,
      'client_secret' => 'secrt',
    ];
    $data = $this->apiTest('post', '/api/token', false, $postData);
    $this->assertSame($data['code'], 400);
    $this->assertSame($data['data']['error_description'], 'Missing parameters: "username" and "password" required');
  }

  /**
   * @test
   */
  public function testToken4()
  {
    $postData = [
      'grant_type' => 'password',
      'client_id' => 1,
      'client_secret' => 'secrt',
      'username' => '0712345678',
      'password' => 'wrong', // wrong password 
    ];
    $data = $this->apiTest('post', '/api/token', false, $postData);
    $this->assertSame($data['code'], 401);
    $this->assertSame($data['data']['error_description'], 'Invalid username and password combination');
  }

  /**
   * @test
   */
  public function testToken5()
  {
    $postData = [
      'grant_type' => 'password',
      'client_id' => 1,
      'client_secret' => 'secrt',
      'username' => '0712345678',
      'password' => 'test', // correct password 
    ];
    $data = $this->apiTest('post', '/api/token', false, $postData);
    $this->assertSame($data['code'], 200);
    $this->assertNotEmpty($data['data']['access_token']);

    return $data['data']['access_token'];
  }

  /**
   * @test
   */
  public function testUser1()
  {
    $data = $this->apiTest('get', '/api/user');
    $this->assertSame($data['code'], 401);
    $this->assertSame($data['data']['error'], 'Not Authorized');
  }

  /**
   * @test
   */
  public function testUser2()
  {
    $data = $this->apiTest('get', '/api/user', '75c248912b64aa353982de4250f309bcad7981aa');
    $this->assertSame($data['code'], 401);
    $this->assertSame($data['data']['error'], 'Not Authorized');
  }

  /**
   * @depends testToken5
   */
  public function testUser3(string $token)
  {
    $data = $this->apiTest('get', '/api/user', $token);
    $this->assertSame($data['code'], 200);
    $this->assertSame($data['data']['phone'], '0712345678');
  }
}
