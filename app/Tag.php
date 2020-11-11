<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Story;

class Tag extends Model
{
    // relatie many-to-many cu Story
    public function stories()
    {
    	return $this->belongsToMany('App\Story');
    }
}
