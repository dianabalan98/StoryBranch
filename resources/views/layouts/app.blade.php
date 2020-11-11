<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>StoryBranch</title>


    <!--FONT AWESOME-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <!-- BOOTSTRAP -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- END OF BOOTSTRAP -->
    

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ URL::asset('js/validations/searchValidations.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/search/searchCategory.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- MY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/formStyles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/userStyles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/storyManager.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/searchBar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/favoriteButtons.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pageStyles/LoginRegisterStyles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pageStyles/storyListsDisplay.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pageStyles/buttonsStyles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pageStyles/backgrounds.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pageStyles/storyStyles.css') }}">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


    <style>
        .sidebarLink {
            
            margin-left: 15px;
        }

        .closeBtn{
            color: white; 
            font-weight: bolder;
            background-color: #7401DF;
            border: none;
            width: 100%;
        }

        .rowLink {
            padding: 10px;
        }

        .rowLink:hover {
            background-color: #5F04B4;
        }

       .title {
            word-wrap: break-word;
       }

       body {
        background-color: white;
       }

       
    </style>
    @yield('stylesheets')


</head>
<body>
    <div id="app">

        <!--MENIU COLAPSABIL-->
        @if(Auth::user())
        <div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none; background-color: #7401DF" id="mySidebar">

            <button class="closeBtn rowLink" onclick="w3_close()">Close &times;</button>

            @foreach($stories as $story)
                <div class="rowLink col-md-12">
                    <a href="{{ route('stories.show', $story->id) }}" class="sidebarLink title" style="color: white">{{ $story->title }}</a>
                </div>
            @endforeach
        </div>
        @endif

        <div id="main">
                   
            <nav class="navbar navbar-expand-md bg-white shadow-sm" id="topNavBar">
                <div class="container">

                    <!--BUTON MENIU COLAPSABIL-->
                    @if(Auth::user())
                    <div class="w3">
                        <button id="openNav" class="w3-button btn-lg icon-book" onclick="w3_open()" style="font-size: 25px; color: #7401DF"></button>&nbsp;
                    </div>
                    @endif

                    <a class="navbar-brand" href="{{ url('/') }}">
                        StoryBranch
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!--START OF SEARCH BAR-->

                    @include('partials.searchBar')

                    <!--END OF SEARCH BAR-->

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->username }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                        <a class="dropdown-item" href="{{ route('user.getProfile', Auth::user()->id) }}">
                                           Author Profile
                                        </a>

                                        <a class="dropdown-item" href="{{ route('user.edit') }}">
                                           Edit User Details
                                        </a>

                                         <a class="dropdown-item" href="{{ route('favorite.favorites') }}">
                                           Favorites
                                        </a>

                                         <a class="dropdown-item" href="{{ route('statistics.statistics') }}">
                                           Statistics
                                        </a>

                                        <a class="dropdown-item" href="{{ route('stories.create') }}">
                                           + Write a Story
                                        </a>


                                        @if(Auth::user()->role == 'admin')
                                            <a class="dropdown-item" href="{{ route('tags.index') }}">
                                               Tags
                                            </a>
                                        @endif

                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>


                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>


            <div id="mainContent">
                @yield('content')

            </div>
        </div>
    </div>

    @yield('scripts')

    <script>
        // COD PT MENIUL COLAPSABIL
        function w3_open() {
          document.getElementById("main").style.marginLeft = "25%";
          document.getElementById("mySidebar").style.width = "25%";
          document.getElementById("mySidebar").style.display = "block";
          document.getElementById("openNav").style.display = 'none';
        }
        function w3_close() {
          document.getElementById("main").style.marginLeft = "0%";
          document.getElementById("mySidebar").style.display = "none";
          document.getElementById("openNav").style.display = "inline-block";
        }
    </script>

    
</body>
</html>
