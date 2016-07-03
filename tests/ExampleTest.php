<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Helpers;
use App\Models\User;

class ExampleTest extends TestCase
{
    public function setUp() {
        parent::setUp();
        Artisan::call('migrate');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        User::create([
            'name' => 'TestUser',
            'email' => 'test@test.com',
            'apikey' => str_random(Helpers::API_KEY_LENGTH),
            'password' => Hash::make(str_random(16), ['rounds' => config('upste.password_hash_rounds')]),
        ]);

        $this->assertTrue(User::all()->count() == 1);
    }
}
