@extends('layouts.app')
@section('stylesheets')
    <style>
        .subtitle {
            word-wrap: break-word;
            font-weight: lighter;
        }

        .subtitle:hover {
            color: #8B71F0;
        }
    </style>

@endsection


@section('content')
<div class="container">

	@include('partials.storyManagerMenu')
	<br>	
	<a href="{{ route('nodes.createNode', $story->id) }}" class="btn addNewContentBtn">+ New Fragment</a>&nbsp;

	<table class="table table-hover">
		<thead>
			<tr>
				<th scope="col">Fragment</th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
	@foreach($nodes as $node)

        <!--Lista fragmente-->
        <tr>
      		<th scope="row">
                <a class="subtitle" href="{{ route('nodes.showNode', [$story->id, $node->id]) }}">{{ $node->subtitle }}</a>
            </th>

            <th scope="row">
        		<a href="{{ route('nodes.editNodes', [$story->id, $node->id]) }}" class="btn fragmentBtn editFragmentBtn">Edit</a>
        	</th>

        	<th scope="row">
	            <form action="{{ route('nodes.destroy', $node->id) }}" method="post">
	                @method('delete')
	                @csrf

	                <button type="submit" class="btn fragmentBtn deleteFragmentBtn">Delete</button>
	            </form>
	        </th>
        </tr>
    @endforeach
    </table>
</div>
@endsection


@section('scripts')
    
    <script type="text/javascript">
      

    </script>

@endsection