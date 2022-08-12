<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CurrencyTest extends TestCase
{


    public function testGetAvailableCurrencies()
    {
        $user = $this->getUser();
        $headers = ['Authorization' => "Bearer ".$user->api_token];

        $this->json('GET', '/api/currency/list', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                'status' => "Success",
                'data' => [
                    "USD",
                    "AUD",
                    "EUR",
                    "CAD",
                    "HKD"
                ]
            ]);
    }

    public function testGetCurrenciesLiveRate() {
        $user = $this->getUser();
        $headers = ['Authorization' => "Bearer ".$user->api_token];

        $this->json('GET', '/api/currency/live_rate', ['source'=>'USD','currencies'=>'EUR'], $headers)
            ->assertStatus(200)
            ->assertJson([
                'status' => "Success",
            ])->assertSee("USD");

    }

    public function testGetPeriodStatisOneYear() {
        $user = $this->getUser();
        $headers = ['Authorization' => "Bearer ".$user->api_token];
        $this->json('GET', '/api/currency/period_statis', ['source'=>'USD','destination'=>'EUR', 'period'=>'oneyear'], $headers)
            ->assertStatus(200)
            ->assertJson([
                'status' => "Success",
            ])
            ->assertJsonStructure([
                'data' => [
                   ["rate","title"]
                ],
            ]);
    }
    public function testGetPeriodStatisHalfYear() {
        $user = $this->getUser();
        $headers = ['Authorization' => "Bearer ".$user->api_token];
        $this->json('GET', '/api/currency/period_statis', ['source'=>'USD','destination'=>'EUR', 'period'=>'halfyear'], $headers)
            ->assertStatus(200)
            ->assertJson([
                'status' => "Success",
            ])
            ->assertJsonStructure([
                'data' => [
                    ["rate","title"]
                ],
            ]);
    }

    public function testGetPeriodStatisOneMonth() {
        $user = $this->getUser();
        $headers = ['Authorization' => "Bearer ".$user->api_token];
        $this->json('GET', '/api/currency/period_statis', ['source'=>'USD','destination'=>'EUR', 'period'=>'onemonth'], $headers)
            ->assertStatus(200)
            ->assertJson([
                'status' => "Success",
            ])
            ->assertJsonStructure([
                'data' => [
                    ["rate","title"]
                ],
            ]);
    }

    private function getUser() {
        $user = User::firstOrCreate(
            [
                'email' => 'test@test.com'
            ],
            [
                'name' => 'Test',
                'password' => Hash::make('123456')
            ]
        );
       $user->generateToken();
       return $user;
    }

}
