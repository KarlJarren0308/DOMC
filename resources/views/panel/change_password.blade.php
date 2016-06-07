@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <div class="navbar-element logo"><img src="/img/logo.png"></div>
            <div class="navbar-element title">De Ocampo Memorial College</div>
            <div class="u-pull-right">
                <a href="{{ route('main.getOpac') }}" class="navbar-element">OPAC</a>
                <a href="{{ route('panel.getIndex') }}" class="navbar-element active">Control Panel</a>
                @if(session()->has('username'))
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
                @endif
            </div>
        </div>
    </div>
    <div id="main-container" class="container-fluid">
        <div class="row">
            <div class="three columns">
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{ route('panel.getIndex') }}">Home</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getLoan') }}">Loan Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReserved') }}">Reserved Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReceive') }}">Receive Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'materials') }}">Manage Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item{{ ($what == 'students' ? ' active' : '') }}"><a href="{{ route('panel.getManage', 'students') }}">Manage Students</a></li>
                    <li class="list-group-item{{ ($what == 'faculties' ? ' active' : '') }}"><a href="{{ route('panel.getManage', 'faculties') }}">Manage Faculties</a></li>
                    <li class="list-group-item{{ ($what == 'librarians' ? ' active' : '') }}"><a href="{{ route('panel.getManage', 'librarians') }}">Manage Librarians</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'holidays') }}">Manage Holidays</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReports') }}">Library Reports</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Manage {{ ucfirst($what) }} - Change Password</div>
                <div class="row">
                    <div class="six columns">
                        <p class="text-justify">This module allows you to change the someone's password.</p>
                        <table class="u-full-width">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="2">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-right" width="25%">Name:</td>
                                    <td>
                                        <strong>
                                            @if(strlen($who['Middle_Name']) > 1)
                                                {{ $who['First_Name'] . ' ' . substr($who['Middle_Name'], 0, 1) . '. ' . $who['Last_Name'] }}
                                            @else
                                                {{ $who['First_Name'] . ' ' . $who['Last_Name'] }}
                                            @endif
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="six columns">
                        {!! Form::open(array('route' => array('panel.postChangePassword', $what, $id))) !!}
                            <div class="input-block">
                                {!! Form::label('newPassword', 'New Password:') !!}
                                {!! Form::password('newPassword', array('class' => 'u-full-width', 'placeholder' => 'Enter New Password Here', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
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
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/panel.change_password.js"></script>
@stop