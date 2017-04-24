<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // $response = $this->get('/');
        // $response->assertStatus(200);
        $this->visit('/register')
                ->type('18669783161', 'mobile')
                ->type('1111', 'authCode')
                ->type('partoo', 'name')
                ->type('password', 'password')
                ->type('repassword', 'password')
                ->press('完成注册');
    }
}
