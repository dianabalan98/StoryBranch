<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Node;
use App\Story;
use App\NodeRelation;
use Session;
use Auth;

class ReadingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function checkUser($story) {

        if (Auth::user()->id == $story->author_id) {

            return true;
        }
        else {
            return false;
        }
    }

    public function getOverview($story_id) {
        $story = Story::find($story_id);
        if($story->published == 1) {
            $author = $story->getAuthor();
            $category = $story->getCategory();
            return view('readViews/overview')->with('story', $story)->with('author', $author)->with('category', $category);
        }
        else return redirect()->back();
        
    }

    // SESSION('reading_details') trebuie setata cu $backTo si $history nule chiar cand se da click
    // pe buton "preview" sau "read_story"
    // ar trebui cate o variabila de sesiune pentru fiecare poveste pe care o acceseaza utilizatorul???

    public function startReading($story_id) {
    	$backTo = null; 
		$history = array();
        //tot aici declar si variabila ratings
        $ratings_path = array();

		session()->put('backTo', $backTo);
		session()->put('history', $history);
        session()->put('ratings_path', $ratings_path);

		return $this->readDelegate($story_id);

    }


    // functia DELEGAT care trimite parametrii fie la firstChoice(), fie la choice()
    // are nevoie de:
    // 1) $story_id,  $node_id
    // 2) session ($backTo, $history)
    public function readDelegate($story_id, $node_id=null) {
    	//Daca $history e gol si $node_id e null, se apeleaza firstChoice
    	if(session()->has('history')) {
    		if(count(session('history'))==0 && $node_id==null) {

    			return $this->firstChoice($story_id);
    		}
    	}
    	if($node_id) {

    		return $this->choice($story_id, $node_id);
    	}
    	

    }


    // functia firstChoice
    // se ajunge aici doar prima data cand se incepe de citit povestea
    // de pe butonul "preview" de la autor
    // sau de pe butonul "start reading" de la cititor
    // se mai poate ajunge aici si atunci cand dam "back" si se ajunge la un prim nod root
    // care poate mai are variante pe acelasi nivel cu el (alte noduri root)
    // catre care vrem sa mergem inapoi la prima alegere din toata povestea

    // are nevoie de:
    // 1) $story_id
    // 2) session ($backTo, $history)
    public function firstChoice($story_id) {

    	$story = Story::find($story_id);
        $flag = $this->checkUser($story);
    	// aflu root nodes
    	$nodes = Node::where([['story_id', $story_id], ['root', 1]])->get();

    	if (count($nodes) == 0) {
    		
    		$errorMsg = "This story doesn’t have a root node yet!";
            if($flag == true)
    		    return view('readViews/authorRead')->with('story', $story)->with('errorMsg', $errorMsg);
            else
                return view('readViews/readerRead')->with('story', $story)->with('errorMsg', $errorMsg);
    	}
    	elseif (count($nodes) > 1) {

    		$backTo = "roots"; 
			session()->put('backTo', $backTo);
            if($flag == true)
    		    return view('readViews/authorRead')->with('story', $story)->with('nodes', $nodes)->with('backTo', $backTo);
            else
                return view('readViews/readerRead')->with('story', $story)->with('nodes', $nodes)->with('backTo', $backTo);
    	}
    	elseif (count($nodes) == 1) {

    		$node = $nodes[0];
    		$history = session('history');
    		array_push($history, $node->id);  
    		session()->put('history', $history);

    		$relations = $this->findChildren($node->id);
            if($flag == true)
    		    return view('readViews/authorRead')->with('story', $story)->with('node', $node)->with('relations', $relations);
            else
                return view('readViews/readerRead')->with('story', $story)->with('node', $node)->with('relations', $relations);
    	}
    	

    }


    // functia choice
    // se apeleaza in restul cazurilor cand selectam un choice catre urm fragment/nod de poveste
    // are nevoie de:
    // 1) $story_id,  $node_id
    // 2) session ($backTo, $history)
    public function choice($story_id, $node_id) {

    	$story = Story::find($story_id);
        $flag = $this->checkUser($story);
    	$node = Node::find($node_id);
    	$history = session('history');

    	// inseamna ca am citit deja minim un nod
    	if(count($history) > 0) {
			
			//setam backTo cu id-ul ultimului nod accesat
			$backTo = last($history); 
			session()->put('backTo', $backTo);
    	}
    	elseif(count($history) == 0) {

    		$backTo = "roots"; 
			session()->put('backTo', $backTo);
    	}

    	array_push($history, $node_id);
    	session()->put('history', $history);

    	$relations = $this->findChildren($node->id);

        if($flag == true)
    	   return view('readViews/authorRead')->with('story', $story)->with('node', $node)->with('relations', $relations)->with('backTo', $backTo);
        else
            return view('readViews/readerRead')->with('story', $story)->with('node', $node)->with('relations', $relations)->with('backTo', $backTo);

    }


    // functia prevNode
    // se apeleaza cand vrem sa mergem la nodul anterior
    // are nevoie de:
    // 1) $story_id,  $node_id
    // 2) session ($backTo, $history)
    public function prevNode($story_id, $node_id=null) {

    	$history = session('history');
        $ratings_path = session('ratings_path');
    	$length = null;
    	$backTo = null;
    	//dd($history);
    	
    	if (count($history) > 1) {
    		// scoatem ultimul id din history (al nodului curent de pe care am dat pe butonul "back")
    		$last =  array_pop($history);
    		session()->put('history', $history);  //actualizez sesiunea

            // scot si ultimul elem din variabila de sesiune ratings_path
            $r = array_pop($ratings_path);
            session()->put('ratings_path', $ratings_path);

    		if(count($history) > 2) {
    			$length = count($history) - 2;
    			$backTo = $history[$length];   //luam penultimul element pt backTo 
    		}
    		elseif(count($history) == 2) {
    			
    			$backTo = $history[0];
    		}
    		elseif(count($history) == 1) {
    			$backTo = "roots";
    		}
			session()->put('backTo', $backTo);

			//dd($history, $backTo);
			$story = Story::find($story_id);
            $flag = $this->checkUser($story);

			$node = Node::find($node_id);
			$relations = $this->findChildren($node->id);

            if($flag == true)
			    return view('readViews/authorRead')->with('story', $story)->with('node', $node)->with('relations', $relations)->with('backTo', $backTo)->with('history', $history);
            else
                return view('readViews/readerRead')->with('story', $story)->with('node', $node)->with('relations', $relations)->with('backTo', $backTo)->with('history', $history);
    	}
    }


    // functia findChildren cauta toate relatiile pentru un parent_id
    public function findChildren($node_id) {

    	$relations = NodeRelation::where('parent_id', $node_id)->get();
    	return $relations;

    }

    public function getAuthor($story_id) {
        $story = Story::find($story_id);
        $author = $story->getAuthor();
        return $author;
    }
    

}
