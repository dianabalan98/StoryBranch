@extends('layouts.app')

@section('stylesheets')
<!--CHART JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('css/statistics/statistics.css') }}">

@endsection


@section('content')
<div class="gradBG1">
<div id="statsContainer" class="container"><br>

	<div id="topStatsBtns" class="btnsHolder statsPanels">
		<select id="selectStory" name="selectStory[]" class="selectStats selectStyle search">
			<option selected disabled>Select story</option>
		</select>
		<select id="selectFragment" name="selectFragment[]" class="selectStats selectStyle search">
			<option selected disabled>Select fragment</option>
		</select>
		<p class="graphicTitle" id="comFrag">Comments per fragment</p>
		<p class="graphicTitle" id="emotionFrag">Emotions per fragment</p>
	</div>

	<div id="leftStatsBtns" class="btnsHolder statsPanels">
		<button id="byCommentsStoryBtn" class="statsPageBtn" title="Comments per story"><img class='statsIcons' src="https://image.flaticon.com/icons/svg/2920/2920047.svg"></button>
		
		<button id="byFavoritesStoryBtn" class="statsPageBtn" title="Favorites per story"><img class='statsIcons' src="https://image.flaticon.com/icons/svg/2892/2892455.svg"></button>
		
	</div>

	<div id="canvas-holder" class="statsPanels">
		<!--AICI VINE CANVAS CHART-->
	</div>
	    
</div><br><br>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/statistics/graphicFunctions.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/statistics/getStatsRequests.js') }}"></script>
@endsection