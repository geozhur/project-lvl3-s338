<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
* A basic functional test example.
*
* @return void
*/
    public function testBasicExample()
    {
        $response = $this->get('/');
        $response->assertResponseOk();
    }
}
