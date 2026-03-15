<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Category;
use App\Models\CategoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemCommentTest extends TestCase
{
    use RefreshDatabase;

    /** ① ログイン済みユーザーはコメントを送信できる */
    public function test_logged_in_user_can_comment()
    {
        $user = User::create([
            'name' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '雑貨']);

        $item = Item::create([
            'name' => 'テスト商品',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明文',
            'item_img_url' => 'test.jpg',
        ]);

        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        $commentData = ['comment' => 'テストコメント'];

        $this->actingAs($user)
            ->post(route('comments.store', $item->id), $commentData)
            ->assertRedirect(); // コメント送信後はリダイレクト想定

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }

    /** ② ログイン前のユーザーはコメントできない */
    public function test_guest_cannot_comment()
    {
        $user = User::create([
            'name' => 'testuser2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '雑貨']);

        $item = Item::create([
            'name' => 'テスト商品2',
            'price' => 2000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明文2',
            'item_img_url' => 'test2.jpg',
        ]);

        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        $commentData = ['comment' => 'コメントできません'];

        $response = $this->post(route('comments.store', $item->id), $commentData);

        // ゲストはリダイレクト（ログインページに飛ばされる想定）
        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'comment' => 'コメントできません',
        ]);
    }

    /** ③ コメントが未入力の場合はバリデーションエラー */
    public function test_comment_required_validation()
    {
        $user = User::create([
            'name' => 'testuser3',
            'email' => 'testuser3@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '雑貨']);

        $item = Item::create([
            'name' => 'テスト商品3',
            'price' => 3000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明文3',
            'item_img_url' => 'test3.jpg',
        ]);

        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        $commentData = ['comment' => ''];

        $response = $this->actingAs($user)
            ->post(route('comments.store', $item->id), $commentData);

        $response->assertSessionHasErrors(['comment']);
    }

    /** ④ コメントが255文字以上の場合はバリデーションエラー */
    public function test_comment_max_length_validation()
    {
        $user = User::create([
            'name' => 'testuser4',
            'email' => 'testuser4@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '雑貨']);

        $item = Item::create([
            'name' => 'テスト商品4',
            'price' => 4000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明文4',
            'item_img_url' => 'test4.jpg',
        ]);

        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        $longComment = str_repeat('a', 256); // 256文字

        $response = $this->actingAs($user)
            ->post(route('comments.store', $item->id), ['comment' => $longComment]);

        $response->assertSessionHasErrors(['comment']);
    }
}
