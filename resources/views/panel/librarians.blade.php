@extends('template')

@section('content')
    @foreach($configs as $config)
        <?php
            switch($config['name']) {
                case 'reservation':
                    $reservation = $config['value'];

                    break;
                default:
                    break;
            }
        ?>
    @endforeach
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <a href="{{ route('main.getIndex') }}" class="navbar-element-brand">
                <div class="navbar-element logo"><img src="/img/logo.png"></div>
                <div class="navbar-element title">De Ocampo Memorial College</div>
            </a>
            <div class="u-pull-right">
                <a href="{{ route('main.getAbout') }}" class="navbar-element">About Us</a>
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
                    @if(isset($reservation) && $reservation == 'Show')
                        <li class="list-group-item"><a href="{{ route('panel.getReserved') }}">Reserved Book(s)</a></li>
                    @endif
                    <li class="list-group-item"><a href="{{ route('panel.getReceive') }}">Receive Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'materials') }}">Manage Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'users') }}">Manage Users</a></li>
                    <li class="list-group-item active"><a href="{{ route('panel.getManage', 'librarians') }}">Edit Accounts</a></li>
                    <!-- <li class="list-group-item"><a href="{{ route('panel.getManage', 'holidays') }}">Manage Holidays</a></li> -->
                    <li class="list-group-item"><a href="{{ route('panel.getReports') }}">Library Reports</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getConfiguration') }}">System Configuration</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Edit Accounts</div>
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
                <div class="tray">
                    <a href="{{ route('panel.getAdd', $what) }}" class="btn btn-orange">Add</a>
                </div>
                <table id="librarians-table" class="u-full-width">
                    <thead>
                        <tr>
                            <th>Librarian Number</th>
                            <th>Librarian's Name</th>
                            <th>Birth Date</th>
                            <th width="40%"></th>
                        </tr>
                        <tbody>
                            @foreach($librarians as $librarian)
                                <tr>
                                    <td>
                                        @foreach($librarian_accounts as $account)
                                            @if($account->Account_Owner == $librarian->Librarian_ID)
                                                {{ $account->Account_Username }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if(strlen($librarian->Librarian_Middle_Name) > 1)
                                            {{ $librarian->Librarian_First_Name . ' ' . substr($librarian->Librarian_Middle_Name, 0, 1) . '. ' . $librarian->Librarian_Last_Name }}
                                        @else
                                            {{ $librarian->Librarian_First_Name . ' ' . $librarian->Librarian_Last_Name }}
                                        @endif
                                    </td>
                                    <td>{{ date('F d, Y', strtotime($librarian->Librarian_Birth_Date)) }}</td>
                                    <td class="text-center">
                                        @if(strlen(session()->has('username')))
                                            <a href="{{ route('panel.getChangePassword', array($what, $librarian->Librarian_ID)) }}" class="btn btn-orange btn-sm">Change Password</a>
                                            <a href="{{ route('panel.getEdit', array($what, $librarian->Librarian_ID)) }}" class="btn btn-green btn-sm">Edit</a>
                                            @if(session()->get('account_owner') != $librarian->Librarian_ID)
                                                <a href="{{ route('panel.getDelete', array($what, $librarian->Librarian_ID)) }}" class="btn btn-red btn-sm">Delete</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/panel.librarians.js"></script>
@stop