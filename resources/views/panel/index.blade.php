@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <div class="navbar-element title">De Ocampo Memorial College</div>
            <div class="u-pull-right">
                <a href="./opac" class="navbar-element">OPAC</a>
                <a href="./panel" class="navbar-element active">Control Panel</a>
                @if(session()->has('username'))
                    <a href="" class="navbar-element">
                        @if(strlen(session()->get('middle_name')) > 1)
                            {{ session()->get('first_name') . ' ' . substr(session()->get('middle_name'), 0, 1) . '. ' . session()->get('last_name') }}
                        @else
                            {{ session()->get('first_name') . ' ' . session()->get('last_name') }}
                        @endif
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div id="main-container" class="container">
        <div class="row">
            <div class="three columns">
                <ul class="list-group">
                    <li class="list-group-item active"><a href="./panel">Home</a></li>
                    <li class="list-group-item"><a href="./panel/manage/materials">Manage Materials</a></li>
                    <li class="list-group-item"><a href="./panel/manage/authors">Manage Authors</a></li>
                    <li class="list-group-item"><a href="./panel/manage/publishers">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="./panel/manage/students">Manage Students</a></li>
                    <li class="list-group-item"><a href="./panel/manage/faculties">Manage Faculties</a></li>
                    <li class="list-group-item"><a href="./panel/manage/settings">System Settings</a></li>
                </ul>
            </div>
            <div class="nine columns">Nine</div>
        </div>
    </div>
@stop