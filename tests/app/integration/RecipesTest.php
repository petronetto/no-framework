<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class RecipesTest extends TestCase
{
    public function testGetRecipes()
    {
        // create our http client (Guzzle)
        $client = new Client('http://localhost:8080', [
            'request.options' => [
                'exceptions' => false,
            ]
        ]);

        // $nickname = 'ObjectOrienter'.rand(0, 999);
        // $data     = [
        //     'nickname'     => $nickname,
        //     'avatarNumber' => 5,
        //     'tagLine'      => 'a test dev!'
        // ];

        $request  = $client->get('/api/v1/recipes', null, null);
        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());

        // $this->assertTrue($response->hasHeader('Location'));
        // $data = json_decode($response->getBody(true), true);
        // $this->assertArrayHasKey('nickname', $data);
    }
}
