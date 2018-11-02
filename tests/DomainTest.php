<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
//use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Mockery;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class Domain extends TestCase
{
    use DatabaseMigrations;

/*
    public function testTableDomain()
    {
        $parameters = [
            'name' => 'http://google.com'
        ];
        $this->post("domains", $parameters, []);
        $this->seeInDatabase("domains", ['name' => 'http://google.com']);
    }
*/
    public function testDomain404()
    {
        $this->get('/domains/1')
            ->assertResponseStatus(404);
    }

  
    public function testDomainAdd()
    {
        $parameters = [
            'name' => 'http://google.com'
        ];

        $this->post("domains", $parameters, []);

        $this->get('/domains/1');

        $this->assertRegExp(
            '/http\:\/\/google\.com/',
            $this->response->getContent()
        );
        
        $this->seeInDatabase("domains", ['name' => 'http://google.com']);
    }

    public function testDomainAddFail()
    {
        $parameters = [
            'name' => 'test'
        ];

        $this->post("domains", $parameters, []);
        $this->get('/');
        $this->assertRegExp(
            '/The given data was invalid/',
            $this->response->getContent()
        );
    }

    public function testDomainPagination()
    {
        factory('App\Domain', 50)->create();
        $thisPage = (new \App\Domain())->paginate();
        $this->assertEquals(15, $thisPage->count());
    }

    public function testDomainGuzzle()
    {


        $headers = [];

        $response = new Response(200, $headers, 'testtest');

        $mock = new MockHandler([
            $response
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $client);

        $parameters = [
            'name' => 'http://google.com/xxx'
        ];

        $this->post("domains", $parameters, []);
        $this->seeInDatabase("domains", ['status_code' => '200', 'body' => 'testtest']);
    }

    public function testDomainSeo()
    {

        $headers = [];
        $body = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Document</title>
            <meta name="description" content="Free Web tutorials">
            <meta name="keywords" content="HTML,CSS,XML,JavaScript">
        </head>
        <body>
            <h1>Hacked by</h1>
        </body>
        </html>';

        $response = new Response(200, $headers, $body);

        $mock = new MockHandler([
            $response
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $client);

        $parameters = [
            'name' => 'http://google.com/xxx'
        ];

        $this->post("domains", $parameters, []);

        $this->seeInDatabase("domains", ['h1' => "Hacked by"]);
        $this->seeInDatabase("domains", ['description' => "Free Web tutorials"]);
        $this->seeInDatabase("domains", ['keywords' => "HTML,CSS,XML,JavaScript"]);
    }
}
