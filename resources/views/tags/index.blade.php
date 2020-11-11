@extends('layouts.app')


@section('stylesheets')
<style type="text/css">
	.tagContainer {
		margin-top: 20px;
	}
</style>
@endsection

@section('content')

	<div class="container tagContainer">
		<div class="row">
			<div class="col-md-8">
				<h1>Tags</h1>
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
						</tr>
					</thead>

					<tbody>
						@foreach($tags as $tag)
						<tr>
							<td>{{ $tag->id }}</td>
							<td><a href="{{ route('tags.show', $tag->id) }}">{{ $tag->name }}</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="col-md-3">
				<div class="well">

					<form method="post" action="{{ route('tags.store') }}">
	                        @csrf

	                        <h2>New Tag</h2>
	                        <label for="name">Name:</label>
	                        <input type="text" name="name" class="form-control"><br>

	                        <button type="submit" class="btn storeEditBtn btn-block btn-h1-spacing">{{ __('Create New Tag')}}</button>
	                </form>
					
				</div>
			</div>	
		</div>
	</div>


@endsection