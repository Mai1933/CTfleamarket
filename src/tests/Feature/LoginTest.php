<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @dataProvider dataproviderValidation
     */
    public function testValidationCheck(array $params, array $messages, bool $expect): void
    {
        $request = new LoginRequest();
        $rules = $request->rules();
        $validator = Validator::make($params, $rules);
        $validator = $validator->setCustomMessages($request->messages());
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
        $this->assertSame($messages, $validator->errors()->messages());
    }

    /**
     * バリデーションチェック用データ
     */
    public static function dataproviderValidation()
    {
        return [
            'email null' => [
                [
                    'email' => null,
                    'password' => 'password123',
                ],
                [
                    'email' => [
                        'メールアドレスを入力してください',
                    ],
                ],
                false
            ],
            'password null' => [
                [
                    'email' => 'test@example.com',
                    'password' => null,
                ],
                [
                    'password' => [
                        'パスワードを入力してください',
                    ],
                ],
                false
            ],
        ];
    }

    /**
     * @test
     */
    public function testWrongLogin()
    {
        $wrongUser = [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass123',
        ];
        $response = $this->post('/login', $wrongUser);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['login' => 'ログイン情報が登録されていません']);
    }

    /**
     * @test
     */
    public function testLoginSuccess()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->post('/login', $loginData);
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function testLogout()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);
        $response = $this->post('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
