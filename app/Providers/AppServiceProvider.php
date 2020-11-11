<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use App\Story;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // cod pt share-uirea acelorasi variabile intre toate view-urile
        // share are nevoie de un key si de un value
        // key e numele datei
        // value e valoarea ei
        // View::share('myname', 'Bendis');

        View::composer('*', function($view) {

            if (Auth::user()) {
                $stories = Story::where(['author_id'=>Auth::user()->id])->get();
                $view->with('stories', $stories);
            }
            
        });
 
    }
}
