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
                    <li class="list-group-item active"><a href="{{ route('panel.getManage', 'material_master') }}">Book Master Data</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'materials') }}">Manage Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'users') }}">Manage Users</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'librarians') }}">Edit Accounts</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'holidays') }}">Manage Holidays</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'weeded') }}">Weeded Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReports') }}">Library Reports</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getConfiguration') }}">System Configuration</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Book Master Data</div>
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
                <div class="row">
                    <div class="eight columns">
                        <div style="border: 1px solid #111; padding: 0.5rem 1rem; margin-bottom: 25px;">
                            <h5 class="no-margin">Book Information</h5>
                            <div class="row">
                                <div class="six columns">
                                    <div><strong>Call Number: </strong><em>{{ $material->Material_Call_Number }}</em></div>
                                    <div><strong>Title: </strong><em>{{ $material->Material_Title }}</em></div>
                                    <div>
                                        <strong>Author(s): </strong>
                                        <em>
                                            <?php $isFirst = true; ?>
                                            @foreach($works_authors as $author)
                                                @if(strlen($author->Author_Middle_Name) > 1)
                                                    {{ $author->Author_First_Name . ' ' . substr($author->Author_Middle_Name, 0, 1) . '. ' . $author->Author_Last_Name }}
                                                @else
                                                    {{ $author->Author_First_Name . ' ' . $author->Author_Last_Name }}
                                                @endif

                                                @if($isFirst)
                                                    <?php $isFirst = false; ?>
                                                @else
                                                    {{ ', ' }}
                                                @endif
                                            @endforeach
                                        </em>
                                    </div>
                                </div>
                                <div class="six columns">
                                    <div><strong>Available Copies: </strong><em>{{ $available_copies }}</em></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tray">
                    <button class="btn btn-orange" data-button="add-accession-button">Add</button>
                </div>
                <table id="accessions-table" class="u-full-width">
                    <thead>
                        <tr>
                            <th>Accession Number</th>
                            <th>ISBN</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accessions as $accession)
                            <tr>
                                <td>{{ $material->Material_Call_Number . '-' .  sprintf('%04d', $accession->Accession_Number) }}</td>
                                <td>{{ $material->Material_ISBN }}</td>
                                <td class="text-center">{{ date('F d, Y', strtotime($accession->Accession_Date_Added)) }}</td>
                                <td class="text-center">{{ ucfirst($accession->Accession_Status) }}</td>
                                <td class="text-center">
                                    @if($accession->Accession_Status !== 'onloan')
                                        <button class="btn btn-green btn-sm" data-button="manage-accession-button" data-var-id="{{ $accession->Accession_Number }}" data-var-status="{{ $accession->Accession_Status }}">Manage Accession</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="add-modal" class="modal">
        <div class="modal-container">
            <div class="modal-header">Add Accession</div>
            <div class="modal-body">
                {!! Form::open(array('route' => array('panel.postAdd', 'accessions'))) !!}
                    {!! Form::hidden('materialID', $material->Material_ID) !!}
                    <div class="input-block">
                        {!! Form::label('copies', 'How many copies:') !!}
                        {!! Form::number('copies', 1, array('class' => 'u-full-width', 'min' => '1', 'placeholder' => '', 'required' => 'required')) !!}
                    </div>
                    <div class="input-block text-right">
                        {!! Form::submit('Save', array('class' => 'btn btn-orange')) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="status-modal" class="modal">
        <div class="modal-container">
            <div class="modal-header">Manage Accession</div>
            <div class="modal-body">
                {!! Form::open(array('route' => array('panel.postChangeAccessionStatus'))) !!}
                    {!! Form::hidden('materialID', $material->Material_ID) !!}
                    {!! Form::hidden('accessionNumber', null) !!}
                    <div class="input-block">
                        {!! Form::label('accessionStatus', 'Accession Status:') !!}
                        <select name="accessionStatus" id="accessionStatus" class="u-full-width" required>
                            <option value="" selected disabled>Select a status...</option>
                            <option value="available">Available</option>
                            <option value="archived">Archived</option>
                            <option value="lost">Lost</option>
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
    <script src="/js/panel.material_master_accessions.js"></script>
@stop