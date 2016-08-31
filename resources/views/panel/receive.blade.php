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
    <?php
        function isHoliday($date, $holidays) {
            $date = date('Y-m-d', strtotime($date));

            if(count($holidays) > 0) {
                foreach($holidays as $holiday) {
                    if($holiday->Holiday_Type == 'Suspension') {
                        if($date == date('Y-m-d', strtotime($holiday->Holiday_Date))) {
                            return true;
                        }
                    } else if($holiday->Holiday_Type == 'Regular') {
                        if(date('m-d', strtotime($date)) == date('m-d', strtotime($holiday->Holiday_Type))) {
                            return true;
                        }
                    }
                }

                return false;
            } else {
                return false;
            }
        }

        function isWeekend($date) {
            $date = date('l', strtotime($date));

            if($date == 'Sunday') {
                return true;
            } else if($date == 'Saturday') {
                return true;
            } else {
                return false;
            }
        }

        function nextDay($date) {
            return date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }
    ?>
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
                    <li class="list-group-item active"><a href="{{ route('panel.getReceive') }}">Receive Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'materials') }}">Manage Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'users') }}">Manage Users</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'librarians') }}">Edit Accounts</a></li>
                    <!-- <li class="list-group-item"><a href="{{ route('panel.getManage', 'holidays') }}">Manage Holidays</a></li> -->
                    <li class="list-group-item"><a href="{{ route('panel.getReports') }}">Library Reports</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getConfiguration') }}">System Configuration</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Receive Book(s)</div>
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
                <form data-form="search-loan-form">
                    <div class="input-block">
                        {!! Form::label('searchKeyword', 'Search User:') !!}
                        {!! Form::text('searchKeyword', null, array('style' => 'margin-right: 5px; vertical-align: middle;', 'placeholder' => 'Enter User I.D. Number Here', 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                        {!! Form::submit('Search', array('class' => 'btn btn-orange', 'style' => 'vertical-align: middle; height: 38px !important;')) !!}
                    </div>
                </form>
                <div id="loans-table-block"></div>
            </div>
        </div>
    </div>
    <div id="loader-modal" class="modal">
        <div class="modal-container">
            <div class="modal-header"></div>
            <div class="modal-body">
                <div class="text-center gap-top gap-bottom">
                    <br>
                    <span class="fa fa-spinner fa-biggie-size fa-pulse"></span>
                    <br><br>
                    <h3>Now Searching... Please Wait...</h3>
                </div>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/panel.receive.js"></script>
@stop