@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <div class="navbar-element title">De Ocampo Memorial College</div>
            <div class="u-pull-right">
                <a href="./opac" class="navbar-element">OPAC</a>
                @if(session()->has('username'))
                    <a href="" class="navbar-element">
                        @if(strlen(session()->get('middle_name')) > 1)
                            {{ session()->get('first_name') . ' ' . substr(session()->get('middle_name'), 0, 1) . '. ' . session()->get('last_name') }}
                        @else
                            {{ session()->get('first_name') . ' ' . session()->get('last_name') }}
                        @endif
                    </a>
                @else
                    <a href="./login" class="navbar-element">Login</a>
                @endif
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="one column">One</div>
            <div class="eleven columns">Eleven</div>
        </div>
    </div>
@stop