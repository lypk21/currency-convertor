<?php

namespace Tests\Feature;

use App\Models\User;
use App\Utils\Constants;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function testRequiresEmailAndLogin()
    {
        $this->json('POST', 'api/user/login')
            ->assertStatus(Constants::HTTP_CODE_ERROR)
            ->assertJson([
                'message' => 'The email field is required.',
            ]);
    }

    public function testUserLoginsSuccessfully()
    {
        $user = User::firstOrCreate(
            [
                'email' => 'test@test.com'
            ],
            [
                'name' => 'Test',
                'password' => Hash::make('123456')
            ]
        );

        $payload = ['email' => $user->email, 'password' => "123456"];

        $this->json('POST', 'api/user/login', $payload)
            ->assertStatus(Constants::HTTP_CODE_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'api_token',
                ],
            ]);
    }

    public function testsRegistersSuccessfully()
    {

        $payload = [
            'name' => 'John',
            'email' => 'john@toptal.com',
            'password' => Hash::make('toptal123'),
        ];
        User::where('email', $payload['email'])->delete();
        $this->json('post', '/api/user/register', $payload)
            ->assertStatus(Constants::HTTP_CODE_CREATE);
    }

    public function testsRequiresPasswordEmailAndName()
    {
        $this->json('post', '/api/user/register')
            ->assertStatus(Constants::HTTP_CODE_ERROR)
            ->assertJson([
                'message' => 'The name field is required.',
            ]);
    }

}
