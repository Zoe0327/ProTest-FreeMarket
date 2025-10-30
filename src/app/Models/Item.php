<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'price',
        'brand_name',
        'description',
        'item_img_url',
    ];

    // 出品者（ユーザー）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // コンディション（商品の状態）
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // カテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items', 'item_id', 'category_id')
            ->withTimestamps();
    }

    // コメント（1商品に複数）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいね（1商品に複数）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 売却情報（1商品は1つ）
    public function soldItem()
    {
        return $this->hasOne(SoldItem::class);
    }
}
