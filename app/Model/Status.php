<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const STATUS_InStock = 1;
    const STATUS_OutOfStock = 0;

    const Status=[
      1=>'in stock',
      2=>'out of stock'
    ];

    protected $table = 'status';
    protected $guarded = [];

    public function getStatusNameAttribute()
    {
        return self::Status[$this->status];
    }
    
    public function Product()
    {
        return $this->hasOne(Product::class);
    }
}
