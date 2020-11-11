@extends('layouts.app')

@section('content')
<div class="gradBG3">
<div class="container"> 

    @include('partials.storyManagerMenu')
    <br>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Edit Story Project')}}
                </div>


                <div class="card-body">

                    <!-- AFISAM MESAJ DUPA VALIDARE DIN CONTROLLER -->
                    @if(session('success'))

                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>

                    @endif

                    <form method="post" action="{{ route('stories.update', $story->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf

                        <div id="divTitle" class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required value="{{ $story['title'] }}" maxlength="100">
                        </div>

                        <div id="divDescription" class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" required maxlength="1000">{{ $story['description'] }}</textarea> 
                        </div>

                        <div id="divTags" class="form-group">
                            <label for="tags">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ $tags }}" maxlength="700">
                        </div>

                        <div class="form-group">
                            <select class="form-control" id="selectedCategory" name="selectedCategory" required>

                                @foreach($categories as $category)
                                    @if($story->category_id == $category->id)
                                        <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                    @else
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                              
                            </select>
                        </div>

                        <div id="divCover" class="form-group">
                            <label for="cover">Story Cover Image</label>
                            <input type="file" class="form-control-file" id="cover" name="cover" value="{{ $story['cover'] }}">
                        </div>


                        <button id="storySubmitBtn" type="submit" class="btn storeEditBtn">{{ __('Edit Story')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div><br><br>
</div>
@endsection


@section('scripts')
    
    <script type="text/javascript" src="{{ URL::asset('js/validations/storyValidations.js') }}"></script>

@endsection