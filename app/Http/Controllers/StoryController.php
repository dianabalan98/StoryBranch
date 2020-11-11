<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Story;
use App\Category;
use App\Tag;
use App\Node;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// pentru stocarea de imagini (fisiere din form)
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // nu il mai folosesc
    public function index()
    {
        $user = Auth::user()->id;
        $stories = Story::where(['author_id'=>Auth::user()->id])->get();
        //return view('stories.index')->with('user', $user)->with('stories', $stories);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // returnez un view cu pagina de creare story cu variabila categories ca sa avem din ce selecta
        $categories = Category::all();

        return view('stories/create')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validez campurile inainte de a salva
        if($request->tags){
            $this->validate($request, array(
                'title' => 'required|max:100',
                'description' => 'required|max:1000',
                'tags' => 'max:700|regex:/^([a-z0-9]*[\s]?)*$/',
                'cover' => 'mimes:jpeg,png'
            ));
        }
        else {
            $this->validate($request, array(
                'title' => 'required|max:100',
                'description' => 'required|max:1000',
                'cover' => 'mimes:jpeg,png'
            ));
        }

        //  store doar daca validarea de server (mai sus) se efectueaza cu succes
        $story = new Story;
        $story->author_id = Auth::user()->id;
        $story->title = $request->title;
        $story->description = $request->description;
        $story->category_id = $request->selectedCategory;
        if ($request->cover) {

                $cover = $request->file('cover');
                $extension = $cover->getClientOriginalExtension(); // getting cover image extension
                $cover_name =time().'.'.$extension;
                $cover->move('uploads/story', $cover_name);
                $story->cover = $cover_name;
            } 
        $story->save();  
        // procesez stringul de tags
        if (!empty($request->tags)) {
            $tags = preg_split('/\s+/', $request->tags);
            $tags_id_array = array();  
            foreach ($tags as $tag) {
                
                $result = Tag::where('name', $tag)->first();
                if(!$result) {

                    $result = new Tag;
                    $result->name = $tag;
                    $result->save();  // daca nu exista atunci il cream in tabelul Tag
                    $result = Tag::where('name', $tag)->first();
                    $nr = array_push($tags_id_array, $result->id);
                }
                else {
                    $nr = array_push($tags_id_array, $result->id);
                }
            }
            // sincronizare STORY cu TAG => story_tag
            $story->tags()->sync($tags_id_array, false);
        }               
        // creez o instanta de sesiune flash
        // flash - exists for one page request
        // put - exists until the session is removed
        Session::flash('success', 'The story was successfully saved!');
        return redirect()->route('stories.show', $story->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // folosesc helper method find din Eloquent ca sa interoghez BD si nu mai trebuie sa folosesc limbaj SQL
        $story = Story::find($id);
        if (Auth::user()->id == $story->author_id) {
            $category = Category::find($story->category_id);
            return view('stories.show')->with('story', $story)->with('category', $category); //transmitem param si la view
        }
        else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $story = Story::find($id);
        if (Auth::user()->id == $story->author_id) {
           
            $categories = Category::all();

            $tags_name = null;
            foreach ($story->tags as $tag) {
            
                $tags_name = $tags_name." ".$tag->name;
            }
            
            // returnez la view edit.blade.php variabila $story
            return view('stories.edit')->with('story', $story)->with('categories', $categories)->with('tags', $tags_name);
        }
        else {

            return redirect()->back();
        }

        
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
        $story = Story::find($id);

        if($request->tags){
            $this->validate($request, array(
                'title' => 'required|max:100',
                'description' => 'required|max:1000',
                'tags' => 'max:700|regex:/^([a-z0-9]*[\s]?)*$/',
                'cover' => 'mimes:jpeg,png|max:409'
            ));
        }
        else {
            $this->validate($request, array(
                'title' => 'required|max:100',
                'description' => 'required|max:1000',
                'cover' => 'mimes:jpeg,png'
            ));
        }

        $story->author_id = Auth::user()->id;
        $story->title = $request->title;
        $story->description = $request->description;
        $story->category_id = $request->selectedCategory;
        
        if ($request->cover) {
            
                $cover = $request->file('cover');
                $extension = $cover->getClientOriginalExtension(); // getting cover image extension

                $cover_name =time().'.'.$extension;
                $cover->move('uploads/story', $cover_name);

                $story->cover = $cover_name;

            }
        
        $story->update();  //se salveaza in baza de date o noua instanta de poveste


        // AICI procesez stringul de tags
        if (!empty($request->tags)) {
            $tags = preg_split('/\s+/', $request->tags);
            $tags_id_array = array();  // array gol; de adaugat id-urile tagurilor
            // inserez fiecare tag in tabelul Tag daca nu exista 
            foreach ($tags as $tag) {
                
                //$result = Tag::find($tag);
                $result = Tag::where('name', $tag)->first();
                if(!$result) {

                    $result = new Tag;
                    $result->name = $tag;
                    $result->save();  // daca nu exista atunci il cream in tabelul Tag

                    //$result = Tag::find($tag);   // apoi ii aflam id-ul si il salvam in array
                    $result = Tag::where('name', $tag)->first();
                    $nr = array_push($tags_id_array, $result->id);
                }
                else {

                    $nr = array_push($tags_id_array, $result->id);

                }
            }


           // sincronizare STORY cu TAG => story_tag
           // setam parametru true pt suprascriere
           //$story->tags()->sync($tags_id_array, true);
           $story->tags()->sync($tags_id_array, true);
        }
        else {
           $story->tags()->sync(array(), true);
        }
        

        // creez o instanta de sesiune flash
        // flash - exists for one page request
        // put - exists until the session is removed
        Session::flash('success', 'The story was successfully saved!');
        // redirect to another page  
        return redirect()->route('stories.show', $story->id);
       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // cand sterg o poveste vreau sa se stearga si inreg din story_tag
        $story = Story::find($id);
        if (Auth::user()->id == $story->author_id) {

            $story->tags()->detach();

            $story->delete();
            
            Session::flash('success', 'The story was successfully deleted.');
            
            return view('mainPage');
        }
        else {

            return view('mainPage');
        }
    }


    public function setStatus(Request $request, $storyId) {

        $story = Story::find($storyId);
        $result = "error"; 
        if ($request->status == "published") {

            $nodes = Node::where([["story_id", $storyId], ["root", 1]])->get();
            if (count($nodes) > 0) {
                $story->published = 1;
                $story->save();
                $result = "ok";
            }
            else {
                $result = "error";
            }
            
        }
        elseif ($request->status == "unpublished") {
            $story->published = 0;
            $story->save();
            $result = "ok";

        }

        return $result;
    }


    public function checkRoot($storyId) {
        $story = Story::find($storyId);
        $result = "false";
        $roots = Node::where([["story_id", $storyId], ["root", 1]])->get();

        if (count($roots) > 0) {
            $result = "true";
        }
        else {
            $story->published = 0;
            $story->save();
        }

        return $result;
    }
}
