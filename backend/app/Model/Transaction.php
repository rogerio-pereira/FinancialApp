<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'description',
        'amount',
        'type',
        'due_at',
        'category_id',
        'account_id',
        'payed',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'due_at',
    ];

    protected $casts = [
        'due_at' => 'datetime:Y-m-d',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Account()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
