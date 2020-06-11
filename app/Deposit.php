<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';

    protected $fillable = ['user_id','amount','payment_type','transaction_id','rate','net_amount','charge','status','message'];

    public function member()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function bank()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_type');
    }


}
