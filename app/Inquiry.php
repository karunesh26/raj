<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Quotation;
use App\Customer;
use App\User;

class Inquiry extends Model
{
    protected $table = 'inquiry';

    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'inquiry_id', 'inquiry_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }
}