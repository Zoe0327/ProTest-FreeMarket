<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'sending_postcode',
        'sending_address',
        'sending_building',
        'payment_method',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPartnerUser($authUserId)
    {
        // 自分が購入者なら → 相手は出品者
        if ($this->user_id == $authUserId) {
            return $this->item->user;
        }

        // 自分が出品者なら → 相手は購入者
        return $this->buyer;
    }
}
