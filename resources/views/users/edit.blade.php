@extends('layouts.app')

@section('content')
<div class="gradBG3"><br><br>
<div class="container">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Update Profile')}}
                </div>


                <div class="card-body">

                    <!-- AFISAM MESAJ DUPA VALIDARE DIN CONTROLLER -->
                    @if(session('success'))

                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>

                    @endif

                    <form method="post" action="{{ route('user.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div id="divUsername" class="form-group">
                            <label for="username">Username</label>
                            <input type="text" value="{{ $user['username'] }}" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required maxlength="100">
                        </div>

                        <div id="divEmail" class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" value="{{ $user['email'] }}" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-describedby="emailHelp" required maxlength="255">
                        </div>

                        <div id="divBio" class="form-group">
                            <label for="bio">About me</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio">{{ $user['bio'] }}</textarea>
                        </div>

                        <div id="divAvatar" class="form-group">
                            <label for="avatar">Profile Image</label>
                            <input type="file" class="form-control-file" id="avatar" name="avatar" value="{{ $user['avatar'] }}">
                            <label for="avatar">{{ $user['image'] }}</label>
                        </div>


                        <button id="updateUserBtn" type="submit" class="btn storeEditBtn">{{ __('Update Details')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div><br><br>
</div>
@endsection


@section('scripts')
    
     <script type="text/javascript" src="{{ URL::asset('js/validations/userDetailsValidations.js') }}"></script>

@endsection