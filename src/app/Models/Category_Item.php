<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category_Item extends Model
{
    protected $table = 'category_item';
    protected $fillable = [
        'category_id',
        'item_id'
    ];
}
