<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
//        $range      = range(700, 799);
//        $array1     = array(777,699, 800);
//        $isInRange  = (min($array1)>=min($range) && max($array1)<=max($range)) ? "Yes" : "No";
//        echo $isInRange;

        $numbers = array(700,324,555);

        $start = 700;
        $end = 799;

        $result = [];

        foreach($numbers as $num){
            if($num >= $start && $num <= $end) $result[] = $num;
        }

        print_r(count($result));
    }
}
