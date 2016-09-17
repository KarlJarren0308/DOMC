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
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'material_master') }}">Book Master Data</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'materials') }}">Manage Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'users') }}">Manage Users</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'librarians') }}">Edit Accounts</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'holidays') }}">Manage Holidays</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'weeded') }}">Weeded Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReports') }}">Library Reports</a></li>
                    <li class="list-group-item active"><a href="{{ route('panel.getConfiguration') }}">System Configuration</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">System Configuration</div>
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
                <div class="row" style="margin-bottom: 15px;">
                    <!-- <div class="four columns">
                        <div class="panel">
                            <div class="panel-header">Toggle Reservation</div>
                            <div class="panel-body">
                                <p>Show/Hide reservation module.</p>
                                {!! Form::open(array('route' => array('panel.postConfiguration', 'reservation'))) !!}
                                    <?php
                                        foreach($configs as $config) {
                                            if($config['name'] == 'reservation') {
                                                $reservation = $config['value'];
                                            }
                                        }
                                    ?>
                                    <div class="input-block">
                                        {!! Form::label('settingValue', 'What to do:') !!}
                                        <select name="settingValue" id="settingValue" class="u-full-width" required>
                                            <option value="" selected disabled>Select an option...</option>
                                            <option value="Show"{{ ($reservation == 'Show' ? ' selected' : '') }}>Show Reservation</option>
                                            <option value="Hide"{{ ($reservation == 'Hide' ? ' selected' : '') }}>Hide Reservation</option>
                                        </select>
                                    </div>
                                    <div class="input-block text-right">
                                        {!! Form::submit('Save Changes', array('class' => 'btn btn-orange')) !!}
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div> -->
                    <div class="four columns">
                        <div class="panel">
                            <div class="panel-header">Toggle OPAC Display</div>
                            <div class="panel-body">
                                <p>Change the view of the OPAC module.</p>
                                {!! Form::open(array('route' => array('panel.postConfiguration', 'opac'))) !!}
                                    <?php
                                        foreach($configs as $config) {
                                            if($config['name'] == 'opac') {
                                                $opac = $config['value'];
                                            }
                                        }
                                    ?>
                                    <div class="input-block">
                                        {!! Form::label('settingValue', 'What to do:') !!}
                                        <select name="settingValue" id="settingValue" class="u-full-width" required>
                                            <option value="" selected disabled>Select an option...</option>
                                            <option value="1"{{ ($opac == '1' ? ' selected' : '') }}>Display all books</option>
                                            <option value="2"{{ ($opac == '2' ? ' selected' : '') }}>Display only the book(s) related to the keyword.</option>
                                        </select>
                                    </div>
                                    <div class="input-block text-right">
                                        {!! Form::submit('Save Changes', array('class' => 'btn btn-orange')) !!}
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="four columns">
                        <div class="panel">
                            <div class="panel-header">Manage Penalty</div>
                            <div class="panel-body">
                                <p>Change the penalty days and penalty amount per day.</p>
                                {!! Form::open(array('route' => array('panel.postConfiguration', 'penalty'))) !!}
                                    <?php
                                        foreach($configs as $config) {
                                            if($config['name'] == 'penaltyDays') {
                                                $penaltyDays = $config['value'];
                                            } else if($config['name'] == 'penaltyAmount') {
                                                $penaltyAmount = $config['value'];
                                            }
                                        }
                                    ?>
                                    <div class="input-block">
                                        {!! Form::label('days', 'Penalty Days:') !!}
                                        {!! Form::number('days', $penaltyDays, array('class' => 'u-full-width', 'min' => '1', 'required' => 'required')) !!}
                                    </div>
                                    <div class="input-block">
                                        {!! Form::label('amount', 'Penalty Amount per day:') !!}
                                        {!! Form::number('amount', $penaltyAmount, array('class' => 'u-full-width', 'min' => '1', 'required' => 'required')) !!}
                                    </div>
                                    <div class="input-block text-right">
                                        {!! Form::submit('Save Changes', array('class' => 'btn btn-orange')) !!}
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop