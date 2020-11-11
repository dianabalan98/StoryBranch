<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Story;
use App\User;
use App\Category;
use App\Tag;
use Auth;
use DB;

class SearchController extends Controller
{
    public function searchStory(Request $request) {

    	$this->validate($request, array(
            'searchValue' => 'required|max:255|regex:/^[A-Za-z0-9 _]*$/'
        ));

        $stories = array();


        // trebuie facut join cu tabela autor pt a prelua numele autorului (in story avem doar id_autor)
        // join si cu tabela category pt numele categoriei (in story avem doar id_categorie)
        // trebuie sa preiau doar povestile published = 1
        if ($request->searchValue != "") {

        	if($request->searchType == "title") {

        		$stories = Story::join('categories', 'categories.id', '=', 'stories.category_id')
        						->select('stories.*')
        						->where([['title', 'LIKE', '%'.$request->searchValue.'%'], ['stories.published', '=', 1]])
        						->get()->toArray();

	        }
	        elseif($request->searchType == "author") {

	        	$stories = Story::join('users', 'stories.author_id', '=', 'users.id')
	        					->select('stories.*')
	        					->where([['users.username', 'LIKE', '%'.$request->searchValue.'%'], ['stories.published', '=', 1]])
	        					->get()->toArray();

	        }
	        elseif($request->searchType == "tag") {

	        	//aflare story ids
	        	$tag_id = DB::table('tags')
	        				->where('tags.name', 'LIKE', '%'.$request->searchValue.'%')
	        				->select('tags.id')
	        				->first();
	        	$tag_id = (array) $tag_id;  
	        	        	
	        	if($tag_id) {
	        		$stories = Story::join('story_tag', 'story_tag.story_id', '=', 'stories.id')
	        					->where([['story_tag.tag_id', '=', $tag_id['id']], ['stories.published', '=', 1]])
	        					->select('stories.*')
        						->get()->toArray();
	        	}       		
	        }

	        if(count($stories) > 0) {
	        	// & se adauga pt a putea edita valorile direct in loop
				foreach ($stories as &$story) {
					
					$story  = $this->getDetails($story);  					
				}

		        return view('search/searchResults')->with('results', $stories);
	        }
	        else {
	        	return view('search/searchResults')->with('results', $stories)->with('errorResult', 'No matching stories found!');
	        }   
        }
    }


    public function getDetails($story) {

    	//adaug la array-ul story numele autorului si al categoriei
    	$s = Story::find($story['id']);
		$author = $s->getAuthor();
		$category = $s->getCategory();
		$tags = $s->tags->toArray();
		
		foreach ($tags as &$tag) {
			$tag = $tag['name'];
		}

		$story['author'] = $author['username'];
		$story['author_id'] = $author['id'];
		$story['category'] = $category['name'];
		$story['tags'] = $tags;

		return $story;
    }


    // FUNCTII APELATE DIN MAIN PAGE CU JQUERY
    public function getLastUpdatedStories() {

    	$stories = Story::where('published', '=', 1)->select('stories.*')->orderBy('updated_at', 'desc')->take(3)->get()->toArray();
    	foreach ($stories as &$story) {
					
			$story  = $this->getDetails($story);  					
		}
		return $stories;
    }

    // PENTRU AUTHOR PROFILE => toate povestiile unui autor
    public function getAllPublishedStories($author_id) {

    	$stories = Story::where([['author_id', '=', $author_id], ['published', '=', 1]])->orderBy('updated_at', 'desc')->get()->toArray();

    	foreach ($stories as &$story) {
					
			$story  = $this->getDetails($story);  					
		}
		return $stories;
    }


    // PREIA TOATE CATEGORIILE EXISTENTE
    public function getCategories() {
    	$categories = Category::all()->toArray();
    	return json_encode($categories);
    }


    public function getStoryByCategory($category_id) {

    	$stories = Story::where([['category_id', '=', $category_id], ['published', '=', 1]])->orderBy('updated_at', 'desc')->get()->toArray();

    	foreach ($stories as &$story) {
					
			$story  = $this->getDetails($story);  					
		}
		return view('search/searchResults')->with('results', $stories);
    }
}

