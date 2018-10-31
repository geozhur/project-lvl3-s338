<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
//use GuzzleHttp\Tests\Server;
use Tests\TestCase;

class DbTest extends TestCase
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

    public function testDomainAdd()
    {
        $domain = factory('App\Domain')->create();
        $this->get('/domains/1')
            ->assertResponseStatus(200);
        $this->get('/domains/2')
            ->assertResponseStatus(404);
    }

    public function testDomainPagination()
    {
        factory('App\Domain', 50)->create();
        $thisPage = (new \App\Domain())->paginate();
        $this->assertEquals(15, $thisPage->count());
    }
}
