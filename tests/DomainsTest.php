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

class Domains extends TestCase
{
    use DatabaseMigrations;

    public function testTableDomain()
    {
        $parameters = [
            'name' => 'http://google.com'
        ];
        $this->post("domains", $parameters, []);
        $this->seeInDatabase("domains", ['name' => 'http://google.com']);
    }

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
    }

    public function testDomainAddFail()
    {
        $parameters = [
            'name' => 'test'
        ];

        $this->post("domains", $parameters, []);
        $this->get('/');
        $this->assertNotRegExp(
            '/test/',
            $this->response->getContent()
        );
    }

    public function testDomainPagination()
    {
        factory('App\Domain', 50)->create();
        $thisPage = (new \App\Domain())->paginate();
        $this->assertEquals(15, $thisPage->count());
    }
}
