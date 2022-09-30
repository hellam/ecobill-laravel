<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response()
    {
//        $response = $this->get('/');
//        $response->assertStatus(302);

        echo Carbon::now()->endOfMonth()->addDays(32);
    }
}
