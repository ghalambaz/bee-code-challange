<?php


namespace App\Tests\Controller\Api;


use App\Test\ApiTestCase;

class TokenControllerTest extends ApiTestCase
{
    public function testTokenLogin()
    {
        $user = $this->createUser();
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'ali',
                'PHP_AUTH_PW' => 'password',
            ]
        );
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $response);
        $this->assertEquals($this->getToken($user), $response['token']);
    }

    public function testTokenValidation()
    {
        $user = $this->createUser();
        $this->client->request(
            'POST',
            '/api/access',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => "Bearer {$this->getToken($user)}",
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testInvalidTokenLogin()
    {
        $user = $this->createUser();
        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'ali',
                'PHP_AUTH_PW' => 'fake',
            ]
        );
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayNotHasKey('token', $response);
    }

}
