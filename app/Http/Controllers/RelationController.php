<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Node;
use App\NodeRelation;
use App\Story;
use Auth;
use Session;

class RelationController extends Controller
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

    // functie citire si afisare din DB a tuturor relatiilor pentru aceasta poveste
    // tot in aceasta pagina voi avea access la functiile de store, update, destroy
    public function index(Request $request, $storyId) {

    	// trebuie sa trimit la view:
    	// 1) nodurile povestii curente, pt a le pune in dropdown select
    	// 2) relatiile deja existente in baza de date pentru nodurile din povestea curenta;
    	//    trebuie cautate dupa id-ul nodului parinte

		$story = Story::find($storyId);
        $flag = $this->checkUser($story);

        if ($flag == true) {
            $nodes = Node::where('story_id', $storyId)->get();

            $relations = array();

            foreach ($nodes as $node) {

                $rel = NodeRelation::where('parent_id', $node->id)->get();
                foreach ($rel as $r) {

                    $child= Node::find($r->child_id);
                    $obj = array("id" => $r->id, "parent_subtitle" => $node->subtitle, "parent_id" => $r->parent_id, "choice" => $r->choice, "child_subtitle" => $child->subtitle, "child_id" => $r->child_id);

                    $relations[] = $obj;
                }                
            }

            return view('nodeRelations/index')->with('nodes', $nodes)->with('relations', $relations)->with('story', $story);
        }
        else {
            return redirect()->back();
        }
	}


	// functia de creare a unei noi relatii in tabela NodeRelations
	public function createRelation(Request $request) {

        $this->validate($request, array(
            'parentId' => 'required',
            'choice' => 'required|max:200',
            'childId' => 'required'
        ));

  
        $relation = new NodeRelation;
        $relation->parent_id = $request->parentId;
        $relation->choice = $request->choice;
        $relation->child_id = $request->childId;

        $relation->save();

        $parent = Node::find($request->parentId);
        $child = Node::find($request->childId);

        // cand returnam in Laravel ca response un array (dictionar) se codifica automat ca json
        $myarray = array('relation' => $relation, 'parent_subtitle' => $parent->subtitle, 'child_subtitle' => $child->subtitle);
        return $myarray;
	}


	// functia EDIT
	public function editRelation($id) {

		$relation = NodeRelation::find($id);

    	return response ()->json($relation);

	}


	// functia update
	public function updateRelation(Request $request, $id) {
	    
        $this->validate($request, array(
                'parentId' => 'required',
                'choice' => 'required|max:200',
                'childId' => 'required'
        ));

        $relation = NodeRelation::find($id);
        $relation->parent_id = $request->parentId;
        $relation->choice = $request->choice;
        $relation->child_id = $request->childId;

	    $relation->save();
	    
	    $parent = Node::find($request->parentId);
        $child = Node::find($request->childId);


        return array('relation' => $relation, 'parent_subtitle' => $parent->subtitle, 'child_subtitle' => $child->subtitle);

	}


	public function deleteRelation($id) {

		$relation = NodeRelation::destroy($id);
		return "ok";

	}

    public function checkDuplicateChoice(Request $request) { // pt aceleasi noduri

        $db_data = NodeRelation::where([['parent_id', $request->parentId],['child_id', $request->childId]])->get();
        if (count($db_data) != 0) {
            foreach ($db_data as $data) {
                if($data->choice == $request->choice) {
                    $errorDuplicate = 'Cannot have duplicate relation between same nodes!';
                    return $errorDuplicate;
                }
            }
           
        }

        return;
    }

}
