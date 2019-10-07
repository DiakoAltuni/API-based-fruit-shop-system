<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function status()
    {
        return $this->hasOne(Status::class);
    }
}
