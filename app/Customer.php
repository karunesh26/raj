<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Customer extends Model
{
    protected $table = 'customer_master';

   /*  public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    } */
}