<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Sold extends Model
{
    protected $table='sold';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
