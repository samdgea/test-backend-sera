<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class CustomerTest extends TestCase
{
    public function testListCustomer()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/v1/customers', [
                'Accept' => 'application/json'
            ])->seeJson([
                'success' => true,
                'message' => 'Customer data'
            ])->seeJsonStructure([
                'success',
                'message',
                'data'
            ]);
    }
}
