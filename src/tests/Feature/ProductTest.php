<?php

namespace Tests\Feature;

use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Category_Item;
use App\Models\Purchase;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Storage;


class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        session()->flush();
    }

    // 【商品一覧取得】
    // 全商品を取得できる
    public function testGetAllItems()
    {
        $item1 = Item::factory()->create(['user_id' => 1, 'status' => 'stock']);
        $item2 = Item::factory()->create(['user_id' => 2, 'status' => 'stock']);
        $item3 = Item::factory()->create(['user_id' => 3, 'status' => 'stock']);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('items', function ($items) use ($item1, $item2, $item3) {
            return $items->contains($item1) && $items->contains($item2) && $items->contains($item3);
        });
    }

    // 購入済み商品は「sold」と表示される
    public function testSoldItems(): void
    {
        $item1 = Item::factory()->create(['user_id' => 1, 'status' => 'sold']);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('items', function ($items) use ($item1) {
            return $items->contains($item1);
        });

        $response->assertSee('src="' . asset('storage/sold.png') . '"', false);
    }

    // 自分が出品した商品は表示されない
    public function testExceptOwnItems()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];
        $this->actingAs($user);

        $item1 = Item::factory()->create(['user_id' => $user->id, 'status' => 'stock']);
        $item2 = Item::factory()->create(['user_id' => 2, 'status' => 'stock']);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('items', function ($items) use ($item1, $item2) {
            return !$items->contains($item1) && $items->contains($item2);
        });
    }


    // 【マイリスト一覧取得】
    // いいねした商品だけが表示される
    public function testMylistItems()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);

        $item1 = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);
        $item2 = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);
        $item3 = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);

        Favorite::create([
            'item_id' => $item1->id,
            'user_id' => $user->id
        ]);
        Favorite::create([
            'item_id' => $item2->id,
            'user_id' => $user->id
        ]);
        Favorite::create([
            'item_id' => $item3->id,
            'user_id' => $otherUser->id
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('favorites', function ($favorites) use ($item1, $item2, $item3) {
            return $favorites->contains($item1) && $favorites->contains($item2) && !$favorites->contains($item3);
        });
    }

    // 購入済み商品は「sold」と表示される
    public function testSoldItemsInMylist(): void
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);

        $item1 = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);
        $item2 = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'sold']);


        Favorite::create([
            'item_id' => $item1->id,
            'user_id' => $user->id
        ]);
        Favorite::create([
            'item_id' => $item2->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('favorites', function ($favorites) use ($item1, $item2) {
            return $favorites->contains($item1) && $favorites->contains($item2);
        });

        $response->assertSee('src="' . asset('storage/item_image/' . $item1->item_image) . '"', false);
        $response->assertSee('src="' . asset('storage/sold.png') . '"', false);
    }

    // 自分が出品した商品は表示されない
    public function testExceptOwnItemsInMylist()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);

        $item1 = Item::factory()->create(['user_id' => $user->id, 'status' => 'stock']);
        $item2 = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);

        Favorite::create([
            'item_id' => $item1->id,
            'user_id' => $user->id
        ]);
        Favorite::create([
            'item_id' => $item2->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('favorites', function ($favorites) use ($item1, $item2) {
            return !$favorites->contains($item1) && $favorites->contains($item2);
        });
    }

    // 未承認の場合は何も表示されない
    public function testGuestMylist()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('favorites', function ($favorites) {
            return $favorites->isEmpty();
        });
    }


    // 【商品検索機能】
    // 「商品名」で部分一致検索ができる
    public function testSearchPartialMatch()
    {
        $item1 = Item::factory()->create(['item_name' => 'テストアイテム１', 'user_id' => 3, 'status' => 'stock']);
        $item2 = Item::factory()->create(['item_name' => 'テストアイテム2', 'user_id' => 3, 'status' => 'stock']);
        $item3 = Item::factory()->create(['item_name' => 'サンプルアイテム', 'user_id' => 3, 'status' => 'stock']);

        $keyword = 'テスト';
        $response = $this->post('/', ['keyword' => $keyword]);
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('items', function ($items) use ($item1, $item2, $item3) {
            return $items->contains($item1) && $items->contains($item2) && !$items->contains($item3);
        });
    }

    // 検索状態がマイリストでも保持されている
    public function testSearchPartialMatchInMylist()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);

        $item1 = Item::factory()->create(['item_name' => 'テストアイテム１', 'user_id' => $otherUser->id, 'status' => 'stock']);
        $item2 = Item::factory()->create(['item_name' => 'テストアイテム2', 'user_id' => $otherUser->id, 'status' => 'stock']);
        $item3 = Item::factory()->create(['item_name' => 'サンプルアイテム', 'user_id' => $otherUser->id, 'status' => 'stock']);

        Favorite::create([
            'item_id' => $item1->id,
            'user_id' => $user->id
        ]);
        Favorite::create([
            'item_id' => $item2->id,
            'user_id' => $user->id
        ]);
        Favorite::create([
            'item_id' => $item3->id,
            'user_id' => $user->id
        ]);

        $keyword = 'テスト';
        $response = $this->post('/', ['keyword' => $keyword]);
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('favorites', function ($favorites) use ($item1, $item2, $item3) {
            return $favorites->contains($item1) && $favorites->contains($item2) && !$favorites->contains($item3);
        });
    }


    // 【商品詳細情報取得】
    // 必要な情報が表示される
    // 複数選択されたカテゴリが表示されているか
    public function testItemDetail()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'user_image' => 'user.png',
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'status' => 'stock'
        ]);
        $categoryFashion = Category::create([
            'category_content' => 'ファッション'
        ]);
        $categoryElectronics = Category::create([
            'category_content' => '家電'
        ]);
        Category_Item::create([
            'category_id' => $categoryFashion->id,
            'item_id' => $item->id,
        ]);
        Category_Item::create([
            'category_id' => $categoryElectronics->id,
            'item_id' => $item->id,
        ]);
        Favorite::create([
            'item_id' => $item->id,
            'user_id' => $user->id
        ]);
        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment_content' => 'テストコメント１'
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertViewIs('detail');
        $response->assertViewHas('item', function ($viewItem) use ($item) {
            return $viewItem->id === $item->id &&
                $viewItem->item_image === $item->item_image &&
                $viewItem->item_name === $item->item_name &&
                $viewItem->brand === $item->brand &&
                $viewItem->price === $item->price &&
                $viewItem->description === $item->description &&
                $viewItem->condition === $item->condition;
        });

        $response->assertViewHas('categories', function ($categories) use ($item) {
            return $categories->count() === 2 &&
                $categories[0]['category_content'] === 'ファッション' &&
                $categories[1]['category_content'] === '家電';
        });

        $response->assertViewHas('commentNumber', function ($commentNumber) {
            return $commentNumber === 1;
        });

        $response->assertViewHas('comments', function ($comments) use ($user) {
            return count($comments) === 1 &&
                $comments[0]['content'] === 'テストコメント１' &&
                $comments[0]['user_name'] === $user->name &&
                $comments[0]['user_image'] === $user->user_image;
        });

        $this->assertEquals($item->favorites->count(), 1);
    }


    // 【いいね機能】
    // いいねアイコンを押下することによって、いいねした商品として登録することができる
    // 追加済みのアイコンは色が変化する
    public function testFavorite()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);

        $item = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);

        $response = $this->get("/item/like/{$item->id}");
        $this->assertDatabaseHas('favorites', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
        $this->assertEquals($item->favorites->count(), 1);
        $response->assertViewIs('detail');
        $response->assertSee('src="' . asset('storage/comrade.png') . '"', false);
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる
    public function testCancelFavorite()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);

        $item = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);

        $this->get("/item/like/{$item->id}");
        $response = $this->get("/item/unlike/{$item->id}");
        $this->assertDatabaseMissing('favorites', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
        $this->assertEquals($item->favorites->count(), 0);
        $response->assertViewIs('detail');
    }

    // 【コメント送信機能】
    // ログイン済みのユーザーはコメントを送信できる
    public function testSendComments()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);
        $item = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);
        $comment = 'テストコメント';
        $response = $this->post('/comment', [
            'item_id' => $item->id,
            'comment' => $comment
        ]);
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment_content' => 'テストコメント'
        ]);
        $response->assertViewIs('detail');
        $response->assertViewHas('commentNumber', function ($commentNumber) {
            return $commentNumber === 1;
        });
    }

    // ログイン前のユーザーはコメントを送信できない
    public function testGuestSendComments()
    {
        $item = Item::factory()->create(['user_id' => 1, 'status' => 'stock']);
        $comment = 'テストコメント';

        $response = $this->post('/comment', [
            'item_id' => $item->id,
            'comment' => $comment
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment_content' => $comment,
        ]);
    }

    // コメントが入力されていない場合、バリデーションメッセージが表示される
    // コメントが255字以上の場合、バリデーションメッセージが表示される
    #[\PHPUnit\Framework\Attributes\DataProvider('dataproviderValidation')]
    public function testCommentValidationCheck(array $params, array $messages, bool $expect): void
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);
        $item = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);

        $request = new CommentRequest();
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
            'comment null' => [
                [
                    'item_id' => 1,
                    'comment' => null,
                ],
                [
                    'comment' => [
                        'コメントを入力してください。',
                    ],
                ],
                false
            ],
            'comment over255 characters' => [
                [
                    'item_id' => 1,
                    'comment' => str_repeat('a', 256),
                ],
                [
                    'comment' => [
                        '255文字以内で入力してください。',
                    ],
                ],
                false
            ],
        ];
    }


    // 【商品購入機能】
    // 「購入する」ボタンを押下すると購入が完了する
    // 購入した商品は商品一覧画面にて「sold」と表示される
    // 「プロフィール/購入した商品一覧」に追加されている
    public function testPurchaseFunction()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);
        $item = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);
        $param = [
            'item_id' => $item->id,
            'payment' => 'コンビニ支払い',
        ];
        $response = $this->post('/purchase', $param);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'コンビニ支払い',
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        $response = $this->get('/');
        $response->assertSee('src="' . asset('storage/sold.png') . '"', false);

        $response = $this->get('/mypage');
        $response->assertViewHas('buyItems', function ($buyItems) use ($item) {
            return $buyItems->contains($item);
        });
    }


    // 【支払い方法選択機能】
    // 小計画面で変更が即時反映される
    public function testSelectPey()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);
        $item = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('支払い方法');
        $response->assertSee('コンビニ支払い');
        $response->assertSee('カード支払い');
        $response->assertSee('選択してください', false);
    }


    // 【配送先変更機能】
    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    // 購入した商品に送付先住所が紐づいて登録される
    public function testChangeAddress()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);
        $item = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'stock']);

        $addressParam = [
            'postcode' => '000-0000',
            'address' => '更新後住所1-1-1',
            'building' => '更新後ビル',
        ];
        $response = $this->put("/purchase/address/{$item->id}", $addressParam);

        $response->assertStatus(302);
        $response->assertRedirect('/purchase/' . $item->id);

        $user->refresh();
        $this->assertEquals('000-0000', $user->postcode);
        $this->assertEquals('更新後住所1-1-1', $user->address);
        $this->assertEquals('更新後ビル', $user->building);

        $purchaseParam = [
            'item_id' => $item->id,
            'payment' => 'コンビニ支払い',
        ];
        $response = $this->post('/purchase', $purchaseParam);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'コンビニ支払い',
            'postcode' => '000-0000',
            'address' => '更新後住所1-1-1',
            'building' => '更新後ビル',
        ]);
    }


    //【ユーザー情報取得】
    // 必要な情報が取得できる
    public function testGetUserData()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $otherUser = User::create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password1231'),
            'email_verified_at' => now(),
            'postcode' => '123-4561',
            'address' => '東京都港区1-1-11',
            'building' => 'テストビル1',
        ]);
        $this->actingAs($user);

        $sellItem = Item::factory()->create(['user_id' => $user->id, 'status' => 'stock']);
        $buyItem = Item::factory()->create(['user_id' => $otherUser->id, 'status' => 'sold']);
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'payment' => 'コンビニ支払い',
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        $response = $this->get('/mypage');
        $response->assertStatus(200);
        $response->assertViewIs('profile');

        $response->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->user_image === $user->user_image &&
                $viewUser->name === $user->name;
        });
        $response->assertViewHas('sellItems', function ($sellItems) use ($sellItem) {
            return $sellItems->contains($sellItem);
        });
        $response->assertViewHas('buyItems', function ($buyItems) use ($buyItem) {
            return $buyItems->contains($buyItem);
        });
    }


    // 【ユーザー情報変更】
    // 変更項目が初期値として過去設定されていること
    public function testChangeUserData()
    {
        $user = User::create([
            'name' => 'test',
            'user_image' => 'testImage',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $this->actingAs($user);

        $response = $this->get("/mypage");
        $response->assertViewIs('profile');
        $response->assertViewHas('user', function ($viewUser) use ($user) {
            return $viewUser->user_image === $user->user_image &&
                $viewUser->name === $user->name &&
                $viewUser->postcode === $user->postcode &&
                $viewUser->address === $user->address &&
                $viewUser->building === $user->building;
        });
    }


    // 【出品商品情報登録】
    // 商品出品画面にて必要な情報が保存できること
    public function testRegisterItems()
    {
        Storage::fake('public');
        $user = User::create([
            'name' => 'test',
            'user_image' => 'testImage',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'postcode' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building' => 'テストビル',
        ]);
        $category1 = Category::create([
            'category_content' => 'ファッション'
        ]);
        $category2 = Category::create([
            'category_content' => '家電'
        ]);
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('test_image.jpg', 100);

        $param = [
            'item_image' => $file,
            'item_name' => 'testItem',
            'brand' => 'testBrand',
            'color' => 'testColor',
            'description' => 'test',
            'condition' => '良い',
            'price' => '1000',
            'category' => [$category1->id, $category2->id],
        ];
        $response = $this->post("/sell", $param);
        $response->assertStatus(302);
        $response->assertRedirect("/");
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'item_image' => 'test_image.jpg',
            'item_name' => $param['item_name'],
            'brand' => $param['brand'],
            'color' => $param['color'],
            'description' => $param['description'],
            'condition' => $param['condition'],
            'price' => $param['price'],
        ]);
        $this->assertDatabaseHas('category_item', [
            'item_id' => Item::where('item_name', $param['item_name'])->first()->id,
            'category_id' => $category1->id
        ]);
        $this->assertDatabaseHas('category_item', [
            'item_id' => Item::where('item_name', $param['item_name'])->first()->id,
            'category_id' => $category2->id
        ]);
    }
}
