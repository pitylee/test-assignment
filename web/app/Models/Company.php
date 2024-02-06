<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $with = [
        'wallet',
    ];

    /**
     * The wallet of the company
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'company_id');
    }
}
