@extends('layouts.app')


@section('stylesheets')

<style type="text/css">
	.tagBtn {
		margin-top: 20px;
	}

	.tagContainer {
		margin-top: 20px;
	}
</style>

@endsection

@section('content')

	<div class="container tagContainer">
		<div class="row">
			<div class="col-md-8">
				<h3><b style="color:#8B71F0">{{ $tag->name }}</b> tag  found in: <small>{{ $tag->stories()->count()}}  Stories</small></h3><br>
			</div>
			
			<div class="col-md-2">
				<a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-block pull-right tagBtn storeEditBtn">Edit</a>
			</div>
			<div class="col-md-2">
				<form action="{{ route('tags.destroy', $tag->id) }}" method="post">
					@method('delete')
					@csrf

					<button type="submit" class="btn btn-block tagBtn storeEditBtn">{{ __('Delete')}}</button>
				</form>
			</div>
		</div>

		<!-- Afisez toate povestile care contin acest tag/ e ca un fel de search pt admin -->
		<div class="row">
			<div class="col-md-12">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>Tags</th>
							<th></th>
						</tr>
					</thead>

					<tbody>
						@foreach($tag->stories as $story)

							<tr>
								<th>{{ $story->id }}</th>
								<th><a href='http://localhost:8000/story/overview/{{$story->id}}'/>{{ $story->title }}</th>
								<td>
									@foreach($story->tags as $tag)

										<a href="#" class="badge badge-secondary">{{ $tag->name }}</a>

									@endforeach
								</td>
							</tr>

						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

@endsection