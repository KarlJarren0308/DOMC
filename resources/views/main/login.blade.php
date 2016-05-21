@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <div class="navbar-element title">De Ocampo Memorial College</div>
            <div class="u-pull-right">
                <a href="{{ route('main.getOpac') }}" class="navbar-element">OPAC</a>
                @if(session()->has('username'))
                    <a href="" class="navbar-element">
                        @if(strlen(session()->get('middle_name')) > 1)
                            {{ session()->get('first_name') . ' ' . substr(session()->get('middle_name'), 0, 1) . '. ' . session()->get('last_name') }}
                        @else
                            {{ session()->get('first_name') . ' ' . session()->get('last_name') }}
                        @endif
                    </a>
                @else
                    <a href="{{ route('main.getLogin') }}" class="navbar-element active">Login</a>
                @endif
            </div>
        </div>
    </div>
    <div id="main-container" class="container">
        <div class="banner">Login</div>
        <div class="row">
            <div class="three columns">&nbsp;</div>
            <div class="six columns">
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
                {!! Form::open(array('route' => 'main.postLogin')) !!}
                    <div class="input-block">
                        {!! Form::label('username', 'Username:') !!}
                        {!! Form::text('username', null, array('class' => 'u-full-width', 'placeholder' => 'Enter Username Here', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                    </div>
                    <div class="input-block">
                        {!! Form::label('password', 'Password:') !!}
                        {!! Form::password('password', array('class' => 'u-full-width', 'placeholder' => 'Enter Password Here', 'required' => 'required')) !!}
                    </div>
                    <div class="input-block text-right">
                        {!! Form::submit('Login', array('class' => 'btn btn-orange')) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="three columns">&nbsp;</div>
        </div>
    </div>
@stop