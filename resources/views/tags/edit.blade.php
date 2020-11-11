@extends('layouts.app')


@section('content')

	<div class="container">
		<div class="col-md-8">
			<form action="{{ route('tags.update', $tag->id) }}" method="post">
				@method('put')
				@csrf

				<label for="name"></label>
				<input type="text" name="name" class="form-control" value="{{ $tag->name }}"><br>

				<button type="submit" class="btn storeEditBtn">{{ __('Save Changes')}}</button>
				
			</form>
		</div>
		
	</div>


@endsection