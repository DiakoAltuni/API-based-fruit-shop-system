<?php

namespace App\Transformers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoldTransformer extends Transformer
{
    public function transform($item)
    {
        return [
            'product_name'=>$item->product_name,
            'weight'=>$item->weight,
            'price'=>$item->price,
            'user_id'=>$item->user_id,
        ];

    }
}
