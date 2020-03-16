<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    protected $fillable = [
        'description',
        'amount',
        'type',
        'last_date',
        'category_id',
        'account_id',
        'first_transaction',
        'period',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'last_date',
    ];

    protected $casts = [
        'last_date' => 'datetime:Y-m-d',
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

    public function firstTransaction()
    {
        return $this->belongsTo(Transaction::class, 'first_transaction');
    }
}
