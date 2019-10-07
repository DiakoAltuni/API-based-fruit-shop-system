<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model\Status::class, function (Faker $faker) {
    return [
        'status'=> random_int(0,1)
    ];
});
