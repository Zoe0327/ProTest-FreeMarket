<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public function soldItem()
    {
        return $this->belongsTo(SoldItem::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(user::class, 'reviewer_id');
    }

    public function reviewedUser()
    {
       return $this->belongsTo(User::class, 'reviewed_user_id');
    }
}
