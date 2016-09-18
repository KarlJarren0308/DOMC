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
                    <li class="list-group-item active"><a href="{{ route('panel.getManage', 'weeded') }}">Weeded Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReports') }}">Library Reports</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getConfiguration') }}">System Configuration</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Weeded Books</div>
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
                <table id="weeded-table" class="u-full-width">
                    <thead>
                        <tr>
                            <th>Accession Number</th>
                            <th>Title</th>
                            <th>Call Number</th>
                            <th>ISBN</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accessions as $accession)
                            <tr>
                                <td>{{ $accession->Material_Call_Number . '-' .  sprintf('%04d', $accession->Accession_Number) }}</td>
                                <td>{{ $accession->Material_Title }}</td>
                                <td>{{ $accession->Material_Call_Number }}</td>
                                <td>{{ $accession->Material_ISBN }}</td>
                                <td class="text-center">{{ date('F d, Y', strtotime($accession->Accession_Date_Added)) }}</td>
                                <td class="text-center">{{ ucfirst($accession->Accession_Status) }}</td>
                                <td class="text-center">
                                    <button class="btn btn-green btn-sm" data-button="manage-accession-button" data-var-id="{{ $accession->Accession_Number }}" data-var-status="{{ $accession->Accession_Status }}">Manage</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="modal" class="modal">
        <div class="modal-container">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
        </div>
    </div>
    <div id="status-modal" class="modal">
        <div class="modal-container">
            <div class="modal-header">Weeded Books</div>
            <div class="modal-body">
                {!! Form::open(array('route' => array('panel.postChangeAccessionStatus'))) !!}
                    {!! Form::hidden('materialID', '-1') !!}
                    {!! Form::hidden('accessionNumber', null) !!}
                    <div class="input-block">
                        {!! Form::label('accessionStatus', 'Accession Status:') !!}
                        <select name="accessionStatus" id="accessionStatus" class="u-full-width" required>
                            <option value="" selected disabled>Select a status...</option>
                            <option value="available">Available</option>
                            <option value="archived">Archived</option>
                            <option value="sold">Sold</option>
                            <option value="donated">Donated</option>
                            <option value="weeded">Weeded</option>
                        </select>
                    </div>
                    <div class="input-block text-right">
                        {!! Form::submit('Save', array('class' => 'btn btn-orange')) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/panel.weeded_materials.js"></script>
@stop