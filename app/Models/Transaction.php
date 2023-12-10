<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_buyer",
        "address",
        "discount",
        "payment_method",
        "status",
        "delivery_cost",
    ];
}
