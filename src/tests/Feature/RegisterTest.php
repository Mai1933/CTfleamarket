<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Http\Requests\RegisterRequest;

class RegisterTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataproviderValidation
     */
    public function validationCheck(array $params, array $messages, bool $expect): void
    {
        $request = new RegisterRequest();
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
            'name null' => [
                [
                    'name' => null,
                    'email' => 'test@example.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
                ],
                [
                    'name' => [
                        'お名前を入力してください',
                    ],
                ],
                false
            ],
            'email null' => [
                [
                    'name' => 'テストユーザー',
                    'email' => null,
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
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
                    'name' => 'テストユーザー',
                    'email' => 'test@example.com',
                    'password' => null,
                    'password_confirmation' => null,
                ],
                [
                    'password' => [
                        'パスワードを入力してください',
                    ],
                    'password_confirmation' => [
                        '確認用パスワードを入力してください',
                    ],
                ],
                false
            ],
            'password short' => [
                [
                    'name' => 'テストユーザー',
                    'email' => 'test@example.com',
                    'password' => 'abc1234',
                    'password_confirmation' => 'abc1234',
                ],
                [
                    'password' => [
                        'パスワードは8文字以上で入力してください',
                    ],
                ],
                false
            ],
            'password_confirm different' => [
                [
                    'name' => 'テストユーザー',
                    'email' => 'test@example.com',
                    'password' => 'password123',
                    'password_confirmation' => 'abc1234',
                ],
                [
                    'password' => [
                        'パスワードと一致しません',
                    ],
                ],
                false
            ],
        ];
    }
}
