<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\FavoriteStory;
use App\FavoriteAuthor;
use App\Story;
use App\Comment;
use App\Node;
use App\StorylineRating;

class StatisticsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }


    public function getStatisticsPage() {

        return view('users.statistics');
    }

    public function getCommentsPerStory() {

    	$labels = array();
    	$commentsNr = array();
    	$results = array();

    	$stories = $this->getStoriesForStats();
    	foreach ($stories as $story) {
    		array_push($labels, $story['title']);
    		$comments = Comment::where('story_id', '=', $story['id'])->select('comments.id')->get()->toArray();
    		array_push($commentsNr, count($comments));
    	}
    	$results['labels'] = $labels;
    	$results['comments'] = $commentsNr;

		return json_encode($results);
    }


    public function getFavoritesPerStory() {

    	$labels = array();
    	$favsNr = array();
    	$results = array();

    	$stories = $this->getStoriesForStats();
    	foreach ($stories as $story) {
    		array_push($labels, $story['title']);
    		$favs = FavoriteStory::where('story_id', '=', $story['id'])->select('favorite_stories.id')->get()->toArray();
    		array_push($favsNr, count($favs));
    	}
    	$results['labels'] = $labels;
    	$results['favs'] = $favsNr;

		return json_encode($results);
    	
    }


    public function getStoriesForStats() {

    	$stories = Story::where([['author_id', '=', Auth::user()->id], ['published', '=', 1]])->select('stories.id', 'stories.title')->get()->toArray();
    	return $stories;
    }

    public function getNodesForStats($story_id) {

    	$nodes = Node::where('story_id', '=', $story_id)->select('nodes.id', 'nodes.subtitle')->get()->toArray();
    	return $nodes;
    }


    public function getCommentsPerFragments($story_id) {
    	
    	$labels = array();
    	$commentsNr = array();
    	$results = array();

    	$nodes = $this->getNodesForStats($story_id);

    	foreach ($nodes as $node) {
    		array_push($labels, $node['subtitle']);
    		$comments = Comment::where('node_id', '=', $node['id'])->select('comments.id')->get()->toArray();
    		array_push($commentsNr, count($comments));
    	}
 
    	$results['labels'] = $labels;
    	$results['comments'] = $commentsNr;

		return json_encode($results);
    }


    // extrage stari emotionale votate pt fiecare fragment
    public function getEmotionsPerFragment($node_id) {

    	$storylines = StorylineRating::where('path', 'LIKE', '%'.$node_id.'%')->select('path')->get()->toArray();
    	
    	$emotions = array();

    	foreach ($storylines as $s) {  

    		$path =  json_decode($s['path'], true);
    		$length = count($path);

    		for($i=0; $i<$length; $i++) {

    			if($node_id == $path[$i]['node_id']) {
    				
    				array_push($emotions, $path[$i]['emotion']);
    			}
    			
    		}
    	}

    	$assoc = array_count_values($emotions);
    	$results['labels'] = array_keys($assoc);
    	$results['emotions'] = array_values($assoc);


    	return json_encode($results);

    }

    // nr favs per autor
    public function getFavoritesPerAuthor() {

    	$favs = FavoriteAuthor::where('author_id', '=', Auth::user()->id)->select('favorite_authors.id')->get()->toArray();
    	$results = count($favs);

		return json_encode($results);
    	
    }

}