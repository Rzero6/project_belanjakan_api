<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_item",
        "id_user",
        "rating",
        "detail",
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
