<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tag;
use App\User;
use App\Category;

class Story extends Model
{
    // adaug relatia many-to-many cu modelul Tag
    public function tags() 
    {
    	return $this->belongsToMany('App\Tag');
    }

    public function getAuthor()
    {
    	//se returneaza o colectie greu de interogat in controller, de aceea fac arrays
    	$query = User::where('id', $this->author_id)->select('id', 'username')->first();
    	$author = array();
    	$author['id'] = $query->id;
    	$author['username'] = $query->username;
    	return $author;
    }

    public function getCategory()
    {
    	$query = Category::where('id', $this->category_id)->first();
    	$category = array();
    	$category['id'] = $query->id;
    	$category['name'] = $query->name;
    	return $category;
    }

}
