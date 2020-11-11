<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StorylineRating extends Model
{
    // conversie automata a campului json path in array, fara sa mai fac json_decode la interogare
    protected $casts = [
        'path' => 'array'
    ];
}
