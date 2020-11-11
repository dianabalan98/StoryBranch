@extends('layouts.app')

@section('stylesheets')

    <!--CDn pt WYSIWYG tinymce API editor-->
    <script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>

    <!--Initializare EDITOR cu diversi parametri-->
    <script>
        
        tinymce.init({

            selector: '#body',
            menubar: false,
            min_height: 300
        });

    </script>

@endsection

@section('content')
<div class="gradBG3">
<div class="container">

     @include('partials.storyManagerMenu')
     <br>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Edit Story fragment')}}
                </div>


                <div class="card-body">

                    <!-- AFISAM MESAJ DUPA VALIDARE DIN CONTROLLER -->
                    @if(session('success'))

                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @elseif(session('danger'))

                        <div class="alert alert-danger" role="alert">
                            {{ session('danger') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('nodes.update', $node->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf

                        <div id="divSubtitle" class="form-group">
                            <label for="subtitle">Subtitle</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" required value="{{ $node->subtitle }}" maxlength="100">

                            <p id="errorSubtitle"></p>
                        </div>

                        <div id="divBody" class="form-group">
                            <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" maxlength="200000">{{ $node->body }}</textarea> 
                        </div>

                        

                        <div class="form-check">
                            @if($node->root)
                                <input class="form-check-input" type="checkbox" value="1" id="root" name="root" checked="true">
                            @else
                                <input class="form-check-input" type="checkbox" value="1" id="root" name="root">
                            @endif
                            <label class="form-check-label" for="root">Is root fragment</label><br>

                            @if($node->display_subtitle)
                                <input class="form-check-input" type="checkbox" value="1" id="displaySubtitle" name="displaySubtitle" checked="true">
                            @else
                                <input class="form-check-input" type="checkbox" value="1" id="displaySubtitle" name="displaySubtitle">
                            @endif
                            <label class="form-check-label" for="displaySubtitle">Display the subtitle for readers</label><br>
                        </div><br>

                        <input type="hidden" id="storyId" name="storyId" value="{{ $story->id }}">
        
                        <button id="nodeSubmitBtn" type="submit" class="btn storeEditBtn">{{ __('Update fragment')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div><br><br>
</div>
@endsection


@section('scripts')
    
    <script type="text/javascript" src="{{ URL::asset('js/nodes.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validations/nodeValidations.js') }}"></script>

@endsection