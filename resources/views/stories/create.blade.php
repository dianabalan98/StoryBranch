@extends('layouts.app')

@section('content') 
<div class="gradBG1"><br><br>
<div class="container">   
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Create a Story Project')}}
                </div>


                <div class="card-body">

                    <!-- AFISAM MESAJ DUPA VALIDARE DIN CONTROLLER -->
                    @if(session('success'))

                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>

                    @endif

                    <form method="post" action="{{ route('stories.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div id="divTitle" class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required maxlength="100">
                        </div>

                        <div id="divDescription" class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" required maxlength="1000"></textarea> 
                        </div>

                        <div id="divTags" class="form-group">
                            <label for="tags">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" maxlength="700">
                        </div>

                        <div class="form-group">
                            <select class="form-control" id="selectedCategory" name="selectedCategory" required>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                              
                            </select>
                        </div>

                        <div id="divCover" class="form-group">
                            <label for="cover">Story Cover Image</label>
                            <input type="file" class="form-control-file" id="cover" name="cover" value="/uploads/story/defaultCover.png" >
                        </div>


                        <button id="storySubmitBtn" type="submit" class="btn storeEditBtn">{{ __('Create Story')}}</button>
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