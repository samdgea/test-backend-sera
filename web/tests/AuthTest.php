<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    public function testSuccessAuth()
    {
        $this->json('POST', '/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password'
        ], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'token_type',
                'expires_in'
            ]
        ]);

    }

    public function testFailAuth()
    {
        $this->json('POST', '/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'wr0n9Pas$w0rD'
        ], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->seeJson([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
    }
}
