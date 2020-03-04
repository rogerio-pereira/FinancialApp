<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'name',
        'initialBalance'
    ];
}
