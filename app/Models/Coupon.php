<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'name',
        'discount',
        'code',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
