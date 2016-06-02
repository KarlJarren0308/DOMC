@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <div class="navbar-element logo"><img src="/img/logo.png"></div>
            <div class="navbar-element title">De Ocampo Memorial College</div>
            <div class="u-pull-right">
                <a href="{{ route('main.getOpac') }}" class="navbar-element">OPAC</a>
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
        <div class="banner">Account Information - Change Password</div>
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
        <div class="text-left gap-bottom">
            <a href="{{ route('main.getAccountInfo') }}" class="btn btn-green btn-sm">Go Back</a>
        </div>
        <div class="row">
            <div class="six columns">
                <p class="text-justify">This module allows you to change your own password.</p>
            </div>
            <div class="six columns">
                {!! Form::open(array('route' => 'main.postChangePassword')) !!}
                    <div class="input-block">
                        {!! Form::label('oldPassword', 'Old Password:') !!}
                        {!! Form::password('oldPassword', array('class' => 'u-full-width', 'placeholder' => 'Enter Old Password Here', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                    </div>
                    <div class="input-block">
                        {!! Form::label('newPassword', 'New Password:') !!}
                        {!! Form::password('newPassword', array('class' => 'u-full-width', 'placeholder' => 'Enter New Password Here', 'required' => 'required')) !!}
                    </div>
                    <div class="input-block">
                        {!! Form::label('confirmPassword', 'Confirm Password:') !!}
                        {!! Form::password('confirmPassword', array('class' => 'u-full-width', 'placeholder' => 'Enter Confirm Password Here', 'required' => 'required')) !!}
                    </div>
                    <div class="prompt"></div>
                    <div class="input-block text-right">
                        {!! Form::submit('Save Changes', array('id' => 'submit-button', 'class' => 'btn btn-orange', 'disabled' => 'disabled')) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/main.change_password.js"></script>
@stop