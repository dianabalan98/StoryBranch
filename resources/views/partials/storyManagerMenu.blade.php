
<div class="row" style="min-height:35px; padding: 2px 0px 0px 4px; color: white; background-color: #7401DF">
	<p ><b style="margin-left: 15px;">Story: </b>{{ $story->title }}</p>
</div>

<div class="row">
	<a href="{{ route('stories.show', $story->id) }}" class="btn btn-outline-primary storyLink storyManager">Overview</a>
	<a href="{{ route('nodes.indexNodes', $story->id) }}" class="btn btn-outline-primary storyLink storyManager">Fragments</a>
	<a href="{{ route('relations.index', $story->id) }}" class="btn btn-outline-primary storyLink storyManager">Relations</a>
	<a href="{{ route('read.startReading', $story->id)}}" class="btn btn-outline-primary storyLink storyManager">Preview</a>
</div>