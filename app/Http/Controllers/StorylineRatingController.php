<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use App\StorylineRating;
use App\Story;

class StorylineRatingController extends Controller
{
	// necesita autentificare pt acces
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addRating($node_id, $emotion) {

    	$ratings_path = session('ratings_path');
    	// verificare daca nu am pus deja elementul in sesiune
    	$elem = end($ratings_path);
    	if ($elem['node_id'] == $node_id) {

    		if ($elem['emotion'] != $emotion) {

    			$elem['emotion'] = $emotion;
    			$last =  array_pop($ratings_path);
    			array_push($ratings_path, $elem);
    		}
    	}
    	else {
    		$elem = array("node_id"=>$node_id, "emotion"=>$emotion);
    		array_push($ratings_path, $elem);
    	}
  
		session()->put('ratings_path', $ratings_path);

		return json_encode(session('ratings_path'));
    }	

    // iau din variabila de sesiune ultimul rating
    public function getRating($node_id) {

    	$ratings_path = session('ratings_path');
    	if($ratings_path) {
    		$last = end($ratings_path);
    		if($last['node_id'] == $node_id) {
    			return $last['emotion'];
    		}
    		else return null;
    	}
    	else return null;
    	
    }


    public function checkStorylineRating($story_id) {

    	$reader_id = Auth::user()->id;
    	$ratings_path = session('ratings_path');

    	$duplicate = false;
    	$found_storyline = null;

    	$storylines = StorylineRating::where([['story_id', '=', $story_id], ['reader_id', '=', $reader_id]])->select('*')->get()->toArray();


    	foreach ($storylines as $storyline) {
    		$path =  json_decode($storyline['path'], true); // true, for associative array  
    		$length = count($ratings_path);

    		for($i=0; $i<$length; $i++) {

    			if($path[$i]['node_id'] == $ratings_path[$i]['node_id']) {

    				$duplicate = true;
    				continue;
    			}
    			else {
    				$duplicate = false;
    				break;
    			}
    		}

    		if($duplicate) {
    			$found_storyline = $storyline;
    		}
    		
    	}

    	if($found_storyline){
    		return json_encode($found_storyline);
    	}
    	return null;
    }


    public function saveStorylineRating($story_id, $score) {

    	$ratings_path = session('ratings_path');

    	$elem = new StorylineRating;
    	$elem->story_id = $story_id;
    	$elem->reader_id = Auth::user()->id;
    	$elem->path = json_encode($ratings_path);
    	$elem->score = $score;

    	$elem->save();

    	$json_elem = json_encode($elem);
    	$recs = $this->findLevenshteinMatch($json_elem);
    	return $recs;
    }


    
    public function findLevenshteinMatch($elem) {  
    	// returneaza id-ul unui alt cititor cu o buna potrivire sentimentala pe acelasi storyline
    	$storyline = json_decode($elem, true);

    	$storylines = StorylineRating::where([['story_id', '=', $storyline['story_id']], ['reader_id', '!=', $storyline['reader_id']]])->select('reader_id', 'path')->get()->toArray();

    	// storyline-ul pt care cautam match
    	$A = json_decode($storyline['path'], true);  // storyline cititor A
    	$length = count($A);

    	$match = null;
    	$min_score = 9999;

    	foreach ($storylines as $B) {  // storyline cititor B

    		$score = 0;
    		$path =  json_decode($B['path'], true);

    		for($i=0; $i<$length; $i++) {

    			if($A[$i]['node_id'] == $path[$i]['node_id']) {
    				$score += levenshtein($path[$i]['emotion'], $A[$i]['emotion']);
    			}
    			else break;  // nu se compara acelasi fir narativ => trecem la urm
    		}
    		if($score < $min_score) {
    			$min_score = $score;
    			$match = $B['reader_id'];
    		}
    	}

    	// daca nu a mai citit vreun alt cititor povestea, returnez null 
    	if($match == null) {
    		return null;
    	}
    	else {
    		$reader_A = Auth::user()->id;
    		$recs = $this->recommendStoryline($reader_A, $match);  //match = reader B
    		return $recs;
    	} 	

    }


    public function getStoryline(Request $request) {
    	
    	//$json_storyline = json_decode($request->storyline, true);
    	$recs = $this->findLevenshteinMatch($request->storyline);

    	return $recs;
    }
    

    // functie recomandare fir de la cititorul B, cu un scor peste pragul stabilit
    // firul narativ nu trebuie sa fie citit de cititor si nu trebuie sa ii apartina (sa fie autorul ei)
    public function recommendStoryline($reader_A, $reader_B) {

    	// cauta toate povestile citite de B unde scorul >= 7 
    	// apoi elimina povestile citite de A
    	// elimina povestile care apartin lui A (pt care e autor)
    	// salveaza intr-o variabila primele 3 fire narative (daca sunt)
    	// pt fiecare fac join cu tabela story pt a prelua story_name cand afisez in pagina
    	// returnez acest array de rezultate

    	
    	$storylines_B = StorylineRating::where([['reader_id', '=', $reader_B], ['score', '>=', 7]])->get()->toArray();
    	$matches_nr = 0;
    	$matches = array();

    	foreach ($storylines_B as $storyline) {
    		
    		$possibly_read = StorylineRating::where([['reader_id', '=', $reader_A], ['story_id', '=', $storyline['story_id']]])->select('story_id')->get()->toArray();

    		if ($possibly_read) {
    			continue;
    		}
    		else {
    			$possibly_owned = Story::where([['id', '=', $storyline['story_id']], ['published', '=', 1]])->first();
    			
    			if ($possibly_owned['author_id'] == $reader_A) {
    				continue;
    			}
    			else {
    				
    				$matches_nr++;
    				$storyline['story_title'] = $possibly_owned['title'];  
    				// preiau doar nodurile separate din path-ul se sentimente
    				$path = json_decode($storyline['path'], true);
    				$nodes = array();
    				$length = count($path);
    				for($i=0; $i<$length; $i++) {
    					$nodes[$i] = $path[$i]['node_id'];
    				}

    				$storyline['nodes'] = $nodes;
    				array_push($matches, $storyline);
    			}
    		}

    		if ($matches_nr == 3) {
    			break;
    		}
    	}

    	return json_encode($matches);


    	// daca nu gasesc o poveste cu scor bun atunci poate ar trebui sa caut alt cititor cu match bun pt A?
    	// sau nu ii recomand nimic? 
    	// sau pur si simplu ii recomand una din noile povesti pe care nu le-a citit?
    	
    	// functia asta e apelata din Lev => deci daca nu obtin macar o poveste de recomandat as putea incerca un
    	// alt match pt cititorul A => urmatorul cititor B
    }


    // setare var sesiune doar cu nodurile care trebuie parcurse din storyline-ul recomandat
    public function setRecSession($storyline_id) {

    	$storyline = StorylineRating::where("id", '=', $storyline_id)->first();
    	$path = json_decode($storyline['path'], true);

		$nodes = array();
		$rec_nodes = array();
		$rec_nodes['story_id'] = $storyline['story_id'];
		$length = count($path);

		for($i=0; $i<$length; $i++) {
			$nodes[$i] = $path[$i]['node_id'];
		}
		$rec_nodes['nodes'] = $nodes;

		// creare var sesiune
		session()->put('rec_nodes', $rec_nodes);
		return "ok ".json_encode($rec_nodes);
    }

    // preluare sesiune rec ca array (daca exista)
    public function getRecSession($story_id) {
    	
    	$rec_nodes = session('rec_nodes');

    	if($rec_nodes['story_id'] == $story_id) {
    		
    		return json_encode($rec_nodes);
    	}
    	else return null;
    }
}
