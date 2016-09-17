@extends('template')

@section('content')
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
                    <li class="list-group-item active"><a href="{{ route('panel.getReserved') }}">Reserved Book(s)</a></li>
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
                    <li class="list-group-item"><a href="{{ route('panel.getConfiguration') }}">System Configuration</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Reserved Book(s)</div>
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
                <table id="reserved-table" class="u-full-width">
                    <thead>
                        <tr>
                            <th>Call Number</th>
                            <th>Title</th>
                            <th>Author(s)</th>
                            <th>Reserved By</th>
                            <th></th>
                        </tr>
                        <tbody>
                            @foreach($works_reservations as $reservation)
                                @if($reservation->Reservation_Status == 'active')
                                    @foreach($works_materials as $material)
                                        @if($reservation->Material_ID == $material->Material_ID)
                                            <?php $isFirst = true; ?>
                                            <tr>
                                                <td>{{ $material->Material_Call_Number }}</td>
                                                <td>{{ $material->Material_Title }}</td>
                                                <td>
                                                    @foreach($works_authors as $author)
                                                        @if($author->Material_ID == $material->Material_ID)
                                                            @if($isFirst)
                                                                <?php $isFirst = false; ?>
                                                            @else
                                                                <br>
                                                            @endif

                                                            @if(strlen($author->Author_Middle_Name) > 1)
                                                                {{ $author->Author_First_Name . ' ' . substr($author->Author_Middle_Name, 0, 1) . '. ' . $author->Author_Last_Name }}
                                                            @else
                                                                {{ $author->Author_First_Name . ' ' . $author->Author_Last_Name }}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($reservation->Account_Type == 'Faculty')
                                                        @foreach($faculty_accounts as $faculty)
                                                            @if($reservation->Account_Owner == $faculty->Faculty_ID)
                                                                @if(strlen($faculty->Faculty_Middle_Name) > 1)
                                                                    {{ $faculty->Faculty_First_Name . ' ' . substr($faculty->Faculty_Middle_Name, 0, 1) . '. ' . $faculty->Faculty_Last_Name }}
                                                                @else
                                                                    {{ $faculty->Faculty_First_Name . ' ' . $faculty->Faculty_Last_Name }}
                                                                @endif

                                                                @break
                                                            @endif
                                                        @endforeach
                                                    @elseif($reservation->Account_Type == 'Librarian')
                                                        @foreach($librarian_accounts as $librarian)
                                                            @if($reservation->Account_Owner == $librarian->Librarian_ID)
                                                                @if(strlen($librarian->Librarian_Middle_Name) > 1)
                                                                    {{ $librarian->Librarian_First_Name . ' ' . substr($librarian->Librarian_Middle_Name, 0, 1) . '. ' . $librarian->Librarian_Last_Name }}
                                                                @else
                                                                    {{ $librarian->Librarian_First_Name . ' ' . $librarian->Librarian_Last_Name }}
                                                                @endif

                                                                @break
                                                            @endif
                                                        @endforeach
                                                    @elseif($reservation->Account_Type == 'Student')
                                                        @foreach($student_accounts as $student)
                                                            @if($reservation->Account_Owner == $student->Student_ID)
                                                                @if(strlen($student->Student_Middle_Name) > 1)
                                                                    {{ $student->Student_First_Name . ' ' . substr($student->Student_Middle_Name, 0, 1) . '. ' . $student->Student_Last_Name }}
                                                                @else
                                                                    {{ $student->Student_First_Name . ' ' . $student->Student_Last_Name }}
                                                                @endif

                                                                @break
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(strlen(session()->has('username')))
                                                        {!! Form::open(array('route' => 'panel.postLoan', 'class' => 'no-margin')) !!}
                                                            {!! Form::hidden('arg0', 'bcfaa2f57da331c29c0bab9f99543451') !!}
                                                            {!! Form::hidden('arg1', $reservation->Reservation_ID) !!}
                                                            {!! Form::submit('Loan', array('class' => 'btn btn-green btn-sm')) !!}
                                                        {!! Form::close() !!}
                                                    @endif
                                                </td>
                                            </tr>

                                            @break
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/panel.reserved.js"></script>
@stop