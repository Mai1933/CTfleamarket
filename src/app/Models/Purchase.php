<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['item_id', 'user_id', 'payment', 'postcode', 'address', 'building'];
}
