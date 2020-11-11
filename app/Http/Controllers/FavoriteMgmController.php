<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\FavoriteStory;
use App\FavoriteAuthor;
use App\Story;

class FavoriteMgmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkIfReaderIsAuthor($story_id) {

    	$author_id = $this->findAuthor($story_id);
    	$reader_id =  Auth::user()->id;

    	if($author_id['author_id'] == $reader_id){
    		return 'true';
    	}
    	else return 'false';
    }

    public function checkFavorites($story_id) { 

    	$fav_story = FavoriteStory::where([['story_id', '=', $story_id], ['reader_id', '=', Auth::user()->id]])
    								->get()->toArray();

    	$author_id = $this->findAuthor($story_id);

    	$fav_author = FavoriteAuthor::where([['author_id', '=', $author_id['author_id']], ['reader_id', '=', Auth::user()->id]])
    								->get()->toArray();

    	$results = array("fav_story"=>false, "fav_author"=>false);
    	if ($fav_story) {
    		$results['fav_story'] = true;
    	}
    	if ($fav_author) {
    		$results['fav_author'] = true;
    	}
    	return $results;

    }

    public function addFavoriteStory(Request $request) {

    	$fav = new FavoriteStory;
    	$fav->story_id = $request->story_id;
    	$fav->reader_id = Auth::user()->id;
    	$fav->save();

    	return "saved";
    }

    public function addFavoriteAuthor(Request $request) {

    	$author_id = $this->findAuthor($request->story_id);
    	
    	$fav = new FavoriteAuthor;
    	$fav->author_id = $author_id['author_id'];
    	$fav->reader_id = Auth::user()->id;

    	$fav->save();

    	return "saved";	
    }

    public function removeFavoriteStory($story_id) {  

    	$fav_story = FavoriteStory::where([['story_id', '=', $story_id], ['reader_id', '=', Auth::user()->id]])
    								->delete();

    	return 'removed fav story';
    }

    public function removeFavoriteAuthor($story_id) { 
    	
    	$author_id = $this->findAuthor($story_id);

    	$fav_author = FavoriteAuthor::where([['author_id', '=', $author_id['author_id']], ['reader_id', '=', Auth::user()->id]])
    								->delete();

    	return "removed fav author ";
    }

    public function findAuthor($story_id) {

    	$author_id = Story::where('id', '=', $story_id)->select('stories.author_id')->first();
    	return $author_id;
    }


    // FUNCTII PENTRU FAVORITES PAGE
    public function goToFavoritesPage() {

        return view('users.favorites');
    }

    public function getFavoriteStories() {
        // in ordine alfabetica
        // trebuie sa verific si daca povestea e inca publicata
        
        $favorites = FavoriteStory::where('reader_id', '=', Auth::user()->id)->select('favorite_stories.story_id')->get()->toArray();

        $stories = array();
        foreach ($favorites as $id) {
            $story = Story::where([['id', '=', $id], ['published', '=', 1]])->select('stories.id', 'stories.title')->get()->toArray();
            if($story) {
                array_push($stories, $story);
            }
        }
        sort($stories);
        return $stories;

    }

    public function getFavoriteAuthors() {
        // in ordine alfabetica
        $favorites = FavoriteAuthor::where('reader_id', '=', Auth::user()->id)->select('favorite_authors.author_id')->get()->toArray();

        $authors = array();
        foreach ($favorites as $id) {
            $author = User::where('id', '=', $id)->select('users.id', 'users.username')->get()->toArray();
            if($author) {
                array_push($authors, $author);
            }
        }
        sort($authors);
        return $authors;
    }

}
