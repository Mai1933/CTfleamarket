<?php

namespace Tests\Feature;

use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Category_Item;
use Illuminate\Support\Facades\Log;

class ProductTest extends TestCase
{
    use RefreshDatabase;

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

    public function testGuestMylist()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('list');
        $response->assertViewHas('favorites', function ($favorites) {
            return $favorites->isEmpty();
        });
    }

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
}
