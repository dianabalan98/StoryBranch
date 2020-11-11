<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Node;
use App\Story;
use Auth;
use Session;

class NodeController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNodes($storyId)
    {
        // selectez toate nodurile din povestea curent selectata
        $nodes = Node::where('story_id', $storyId)->get();
        $story = Story::find($storyId);
        $flag = $this->checkUser($story);
        if($flag == true) {
            return view('nodes/index')->with('nodes', $nodes)->with('story', $story);
        }
        else {
            return redirect()->back();
        }
        
    }

    public function createNode($storyId)
    {
        $story = Story::find($storyId);
        return view('nodes/create')->with('story', $story);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->body){
            $this->validate($request, array(
                'subtitle' => 'required|max:100',
                'body' => 'max:2000000',
            ));
        }
        else {
            $this->validate($request, array(
                'subtitle' => 'required|max:100'
            ));
        }
        

        $new_node = new Node;
        // verific daca dintre toate nodurile care au acelasi story_id nu mai exista altul cu acelasi subtitle
        // subtitle trebuie sa fie unic pentru o poveste nu unic pentru toata tabela noduri
        $nodes = Node::where('story_id', $request->storyId)->get();
        if($nodes){
            foreach ($nodes as $node) {
                if ($node->subtitle == $request->subtitle) {
                    Session::flash('danger', 'Subtitle must be unique per story!');
                    // returnez continutul tastat deja impreuna cu eroarea ca sa nu se piarda
                    return redirect()->back()->withInput();
                }
            }
        }
            
        $new_node->story_id = $request->storyId;
        $new_node->subtitle = $request->subtitle;
        if($request->body) {
            $new_node->body = $request->body;
        }
        else {
            $new_node->body = 'Your new story fragment.';
        }

        
        if ($request->root) {

            $new_node->root = true;
        }

        if ($request->displaySubtitle) {
            
            $new_node->display_subtitle = true;
        }

        $new_node->save();

        Session::flash('success', 'The fragment was successfully saved!');

        return redirect()->route('nodes.showNode', [$request->storyId, $new_node->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNode($storyId, $id)
    {
        $node = Node::find($id);
        $story = Story::find($storyId);
        return view('nodes.show')->with('node', $node)->with('story', $story);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editNodes($storyId, $id)
    {
        $node = Node::find($id);
        $story = Story::find($storyId);
        return view('nodes/edit')->with('node', $node)->with('story', $story);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->body){
            $this->validate($request, array(
                'subtitle' => 'required|max:100',
                'body' => 'max:2000000',
            ));
        }
        else {
            $this->validate($request, array(
                'subtitle' => 'required|max:100'
            ));
        }
        

        $updatedNode = Node::find($id);
        // verific daca dintre toate nodurile care au acelasi story_id nu mai exista altul cu acelasi subtitle
        // subtitle trebuie sa fie unic pentru o poveste
        // nu unic pentru toata tabela noduri
        $nodes = Node::where('story_id', $request->storyId)->get();
        if($nodes){
            foreach ($nodes as $node) {
                if ($node->subtitle == $request->subtitle && $node->id != $updatedNode->id) {
                    Session::flash('danger', 'Subtitle must be unique per story!');

                    // returnez continutul pt body pt ca ar fi putut fi lung si utilizatorul ar fi putut pierde tot la eroarea de validare pt subtitle
                    // e suficient sa scriu metoda withInput apelata fara nici un parametru
                    // in view apelez {{ old('body') }}
                    return redirect()->back()->withInput();
                }
            }
        }
            
        $updatedNode->story_id = $request->storyId;
        $updatedNode->subtitle = $request->subtitle;
        if($request->body) {
            $updatedNode->body = $request->body;
        }
        else {
            $updatedNode->body = 'Your new story node.';
        }

        
        if ($request->root) {

            $updatedNode->root = true;
        }
        else $updatedNode->root = false;

        if ($request->displaySubtitle) {
            
            $updatedNode->display_subtitle = true;
        }
        else $updatedNode->display_subtitle = false;

        $updatedNode->update();

        Session::flash('success', 'The node was successfully saved!');

        return redirect()->route('nodes.showNode', [$request->storyId, $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $node = Node::find($id);
        $storyId = $node->story_id;
        $node->delete();

        Session::flash('success', 'The node was successfully deleted.');
            
        return  redirect()->route('nodes.indexNodes', $storyId);
    }
}
