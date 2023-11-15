<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "detail",
        "image",
        "price",
        "stock",
        "id_seller",
    ];

    public function seller(){
        return $this->belongsTo(User::class, 'id_seller');
    }
}
