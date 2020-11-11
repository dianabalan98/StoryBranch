<div class="search-container">

    <div class="dropdown">
	  <button id="categorySearch" class="btn dropdown-toggle selectStyle search" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Category
	  </button>
	  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="categoriesLinks">
	   
	  </div>
	</div>
  
    <form action="{{ route('search.searchStory') }}" method="GET" id="searchForm">
    	@csrf
    	<div class="input-group">
    		<div class="col-xs-2">
    			<select class="form-control search" id="searchType" name="searchType">
			     	<option selected class="searchOption" value="title">Title</option>
			    	<option class="searchOption" value="author">Author</option>
			     	<option class="searchOption" value="tag">Tag</option>
			    </select>
    		</div>
	    	
	    	<div class="col-xs-12">
	    		<input id="searchInput" type="text" class="form-control" placeholder="Search..." name="searchValue" required maxlength="100">
	    	</div>

	    	<div class="col-xs-2">
	    		<button type="submit" class="btn icon-search" id="searchBtn"></button>
	    	</div>
	    	
	    </div>
    </form>
</div>



