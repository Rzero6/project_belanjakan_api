<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaction',
        'name',
        'price',
        'amount',
        'rated',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction');
    }
}
