@extends('layouts.app')

@section('stylesheets')
    <style>
        #subtitle {
            word-wrap: break-word;
        }
    </style> 

@endsection

@section('content')
<div class="container">
    
    @include('partials.storyManagerMenu')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table col-md-2">
                <tr>
                    <td>
                        <a href="{{ route('nodes.editNodes', [$node->story_id, $node->id]) }}" class="btn fragmentBtn editFragmentBtn">Edit</a>
                    </td>

                    <td>
                        <form action="{{ route('nodes.destroy', $node->id) }}" method="post">
                            @method('delete')
                            @csrf

                            <button type="submit" class="btn fragmentBtn deleteFragmentBtn">Delete</button>
                        </form>
                    </td>
                </tr>
            </table>
            
            <div class="card">
                <div class="card-header">
                    @if($node->display_subtitle == true)
                        <p id="subtitle">
                        {{ $node->subtitle }}
                        </p>
                    @endif
                </div>


                <div class="card-body">

                    <!-- AFISAM MESAJ DUPA VALIDARE DIN CONTROLLER -->
                    @if(session('success'))

                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>

                    @endif

                    <div class="justify-content-center">
                        {!! $node->body !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
    
    <script type="text/javascript">
      

    </script>

@endsection