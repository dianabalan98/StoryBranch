<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web']], function () {
    // Add your routes here

	Route::get('/', function () {
	    return view('mainPage');
	});

	Auth::routes();

	Route::get('/mainPage', 'MainPageController@index')->name('mainPage');

	// USER ROUTES
	Route::get('/userProfile/{id}', 'UserController@getProfile')->name('user.getProfile');
	Route::get('/edit/user/', 'UserController@edit')->name('user.edit');
	Route::post('/edit/user/', 'UserController@update')->name('user.update');
	Route::post('/checkEmail', 'UserController@checkEmail')->name('user.checkEmail');
	Route::post('/checkUsername', 'UserController@checkUsername')->name('user.checkUsername');

	// STORY ROUTES
	Route::resource('stories', 'StoryController');
	Route::put('/story/setStatus/{storyId}', 'StoryController@setStatus')->name('stories.setStatus');
	Route::get('/story/checkRoot/{storyId}', 'StoryController@checkRoot')->name('stories.checkRoot');

	// NODE ROUTES
	Route::resource('nodes', 'NodeController', ['except' => ['create', 'index', 'edit', 'show']]);
	Route::get('/story/{storyId}/nodes/create', 'NodeController@createNode')->name('nodes.createNode');
	Route::get('/story/{storyId}/nodes/index', 'NodeController@indexNodes')->name('nodes.indexNodes');
	Route::get('/story/{storyId}/nodes/{id}/show', 'NodeController@showNode')->name('nodes.showNode');
	Route::get('/story/{storyId}/nodes/{id}/edit', 'NodeController@editNodes')->name('nodes.editNodes');

	//AJAX RELATION ROUTES
	Route::get( '/story/{storyId}/relations', 'RelationController@index' )->name('relations.index');
	Route::post( '/story/relations/create', 'RelationController@createRelation' )->name('relations.createRelation');
	Route::get('/story/relations/edit/{id}', 'RelationController@editRelation')->name('relations.editRelation');
	Route::put('/story/relations/update/{id}', 'RelationController@updateRelation')->name('relations.updateRelation');
	Route::delete('/story/relations/delete/{id}', 'RelationController@deleteRelation')->name('relations.deleteRelation');
	Route::post('/checkDuplicateChoice', 'RelationController@checkDuplicateChoice')->name('relations.checkDuplicateChoice');

	// READING ROUTES
	Route::get('/story/overview/{storyId}', 'ReadingController@getOverview')->name('read.getOverview');
	Route::get('/story/{storyId}', 'ReadingController@startReading')->name('read.startReading');
	Route::get('/story/{storyId}/{node_id?}', 'ReadingController@readDelegate')->name('read.readDelegate');
	Route::get('/story/{storyId}/{node_id?}/back', 'ReadingController@prevNode')->name('read.prevNode');
	Route::get('/getAuthor/{storyId}', 'ReadingController@getAuthor')->name('read.getAuthor');

	// SEARCH ROUTES
	Route::get('/search', 'SearchController@searchStory')->name('search.searchStory');
	Route::get('/search/last-updated', 'SearchController@getLastUpdatedStories')->name('search.getLastUpdatedStories');
	Route::get('/getAllPublishedStories/{authorId}', 'SearchController@getAllPublishedStories')->name('search.getAllPublishedStories');
	Route::get('/getCategories', 'SearchController@getCategories')->name('search.getCategories');
	Route::get('/getStoryByCategory/{categId}', 'SearchController@getStoryByCategory')->name('search.getStoryByCategory');

	// COMMENT ROUTES
	Route::post('/addComment', 'CommentController@addComment')->name('comment.addComment');
	Route::get('/getComments/{nodeId}', 'CommentController@getComments')->name('comment.getComments');

	// FAVORITE ROUTES
	Route::post('/addFavoriteStory', 'FavoriteMgmController@addFavoriteStory')->name('favorite.addFavoriteStory');
	Route::post('/addFavoriteAuthor', 'FavoriteMgmController@addFavoriteAuthor')->name('favorite.addFavoriteAuthor');
	Route::get('/checkFavorites/{storyId}', 'FavoriteMgmController@checkFavorites')->name('favorite.checkFavorites');
	Route::delete('/removeFavoriteStory/{storyId}', 'FavoriteMgmController@removeFavoriteStory')->name('favorite.removeFavoriteStory');
	Route::delete('/removeFavoriteAuthor/{storyId}', 'FavoriteMgmController@removeFavoriteAuthor')->name('favorite.removeFavoriteAuthor');
	Route::get('/checkIfReaderIsAuthor/{storyId}', 'FavoriteMgmController@checkIfReaderIsAuthor')->name('favorite.checkIfReaderIsAuthor');
	Route::get('/favorites', 'FavoriteMgmController@goToFavoritesPage')->name('favorite.favorites');
	Route::get('/getFavoriteStories', 'FavoriteMgmController@getFavoriteStories')->name('favorite.getFavoriteStories');
	Route::get('/getFavoriteAuthors', 'FavoriteMgmController@getFavoriteAuthors')->name('favorite.getFavoriteAuthors');

	// STATISTICS ROUTES
	Route::get('/statistics', 'StatisticsController@getStatisticsPage')->name('statistics.statistics');
	Route::get('/getCommentsPerStory', 'StatisticsController@getCommentsPerStory')->name('statistics.getCommentsPerStory');
	Route::get('/getFavoritesPerStory', 'StatisticsController@getFavoritesPerStory')->name('statistics.getFavoritesPerStory');
	Route::get('/getFavoritesPerAuthor', 'StatisticsController@getFavoritesPerAuthor')->name('statistics.getFavoritesPerAuthor');
	Route::get('/getStoriesForStats', 'StatisticsController@getStoriesForStats')->name('statistics.getStoriesForStats');
	Route::get('/getNodesForStats/{storyId}', 'StatisticsController@getNodesForStats')->name('statistics.getNodesForStats');
	Route::get('/getCommentsPerFragments/{storyId}', 'StatisticsController@getCommentsPerFragments')->name('statistics.getCommentsPerFragments');
	Route::get('/getEmotionsPerFragment/{nodeId}', 'StatisticsController@getEmotionsPerFragment')->name('statistics.getEmotionsPerFragment');
	
	// Admin middleware
	Route::group(['middleware' => ['auth', 'admin']], function(){

	// TAG ROUTES
	Route::resource('tags', 'TagController', ['except' => ['create']], ['only' => ['index', 'show', 'edit', 'update', 'destroy']]);

	});

	// STORYLINE RATING ROUTES
	Route::post('/addRating/{nodeId}/{emotion}', 'StorylineRatingController@addRating')->name('rating.addRating');
	Route::post('/saveStorylineRating/{storyId}/{score}', 'StorylineRatingController@saveStorylineRating')->name('rating.saveStorylineRating');
	Route::get('/getRating/{nodeId}', 'StorylineRatingController@getRating')->name('rating.getRating');
	Route::get('/checkStorylineRating/{storyId}', 'StorylineRatingController@checkStorylineRating')->name('rating.checkStorylineRating');
	Route::post('/getRecommendation', 'StorylineRatingController@getStoryline')->name('rating.getStoryline');
	Route::post('/setRecSession/{storylineId}', 'StorylineRatingController@setRecSession')->name('rating.setRecSession');
	Route::get('/getRecSession/{storyId}', 'StorylineRatingController@getRecSession')->name('rating.getRecSession');
	
});



