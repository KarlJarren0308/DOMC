@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <a href="{{ route('main.getIndex') }}" class="navbar-element-brand">
                <div class="navbar-element logo"><img src="/img/logo.png"></div>
                <div class="navbar-element title">De Ocampo Memorial College</div>
            </a>
            <div class="u-pull-right">
                <a href="{{ route('main.getAbout') }}" class="navbar-element">About Us</a>
                <a href="{{ route('main.getOpac') }}" class="navbar-element active">OPAC</a>
                @if(session()->has('username'))
                    @if(session()->get('account_type') == 'Librarian')
                        <a href="{{ route('panel.getIndex') }}" class="navbar-element">Control Panel</a>
                    @endif

                    <div class="dropdown">
                        <a class="navbar-element dropdown-toggle">
                            @if(strlen(session()->get('middle_name')) > 1)
                                {{ session()->get('first_name') . ' ' . substr(session()->get('middle_name'), 0, 1) . '. ' . session()->get('last_name') }}
                            @else
                                {{ session()->get('first_name') . ' ' . session()->get('last_name') }}
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('main.getAccountInfo') }}">Account Information</a></li>
                            <li><a href="{{ route('main.getLogout') }}">Logout</a></li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('main.getLogin') }}" class="navbar-element">Login</a>
                @endif
            </div>
        </div>
    </div>
    <div id="main-container" class="container">
        <div class="banner">Online Public Access Catalog</div>
        @if(session()->has('global_status'))
            @if(session()->get('global_status') == 'Success')
                <?php $class = ' success'; ?>
            @elseif(session()->get('global_status') == 'Warning')
                <?php $class = ' warning'; ?>
            @else
                <?php $class = ' danger'; ?>
            @endif

            <div class="alert{{ $class }}">{{ session()->get('global_message') }}</div>
        @endif
        <form data-form="search-opac-form">
            <div class="input-block">
                {!! Form::label('searchKeyword', 'Search for:') !!}
                {!! Form::text('searchKeyword', null, array('style' => 'margin-right: 5px; vertical-align: middle;', 'placeholder' => 'Enter Keyword Here', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                {!! Form::submit('Search', array('class' => 'btn btn-orange', 'style' => 'vertical-align: middle; height: 38px !important;')) !!}
            </div>
        </form>
        <div id="materials-table-block">
        </div>
    </div>
    <div id="book-info-modal" class="modal">
        <div class="modal-container">
            <div class="modal-header">Book Information</div>
            <div class="modal-body"></div>
        </div>
    </div>
    <div id="loader-modal" class="modal">
        <div class="modal-container">
            <div class="modal-header"></div>
            <div class="modal-body">
                <div class="text-center gap-top gap-bottom">
                    <span class="fa fa-spinner fa-4x fa-pulse"></span>
                    <div class="gap-top">
                        Now Searching... Please Wait...
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    @if(strlen(session()->has('username')))
        <script>
            var issetUsername = true;
        </script>
    @else
        <script>
            var issetUsername = false;
        </script>
    @endif
    <script src="/js/main.opac_2.js"></script>
@stop