@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
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
                    <li class="list-group-item"><a href="{{ route('panel.getLoan') }}">Loan Material(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReserved') }}">Reserved Material(s)</a></li>
                    <li class="list-group-item active"><a href="{{ route('panel.getReceive') }}">Receive Material(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'materials') }}">Manage Materials</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'students') }}">Manage Students</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'faculties') }}">Manage Faculties</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'settings') }}">System Settings</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Receive Material(s)</div>
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
                <table id="receive-table" class="u-full-width">
                    <thead>
                        <tr>
                            <th>Call Number</th>
                            <th>Title</th>
                            <th>Author(s)</th>
                            <th>Loaned By</th>
                            <th>Penalty</th>
                            <th></th>
                        </tr>
                        <tbody>
                            @foreach($loans as $loan)
                                @foreach($works_materials as $material)
                                    @if($loan->Material_ID == $material->Material_ID)
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
                                                @if($loan->Account_Type == 'Faculty')
                                                    @foreach($faculty_accounts as $faculty)
                                                        @if($loan->Account_Owner == $faculty->Faculty_ID)
                                                            @if(strlen($faculty->Faculty_Middle_Name) > 1)
                                                                {{ $faculty->Faculty_First_Name . ' ' . substr($faculty->Faculty_Middle_Name, 0, 1) . '. ' . $faculty->Faculty_Last_Name }}
                                                            @else
                                                                {{ $faculty->Faculty_First_Name . ' ' . $faculty->Faculty_Last_Name }}
                                                            @endif

                                                            @break
                                                        @endif
                                                    @endforeach
                                                @elseif($loan->Account_Type == 'Librarian')
                                                    @foreach($librarian_accounts as $librarian)
                                                        @if($loan->Account_Owner == $librarian->Librarian_ID)
                                                            @if(strlen($librarian->Librarian_Middle_Name) > 1)
                                                                {{ $librarian->Librarian_First_Name . ' ' . substr($librarian->Librarian_Middle_Name, 0, 1) . '. ' . $librarian->Librarian_Last_Name }}
                                                            @else
                                                                {{ $librarian->Librarian_First_Name . ' ' . $librarian->Librarian_Last_Name }}
                                                            @endif

                                                            @break
                                                        @endif
                                                    @endforeach
                                                @elseif($loan->Account_Type == 'Student')
                                                    @foreach($student_accounts as $student)
                                                        @if($loan->Account_Owner == $student->Student_ID)
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
                                            <td>Not yet available</td>
                                            <td class="text-center">
                                                @if(strlen(session()->has('username')))
                                                    @if($loan->Loan_Status == 'active')
                                                        {!! Form::open(array('route' => 'panel.postLoan', 'class' => 'no-margin')) !!}
                                                            {!! Form::hidden('arg0', 'bcfaa2f57da331c29c0bab9f99543451') !!}
                                                            {!! Form::submit('Receive', array('class' => 'btn btn-green btn-sm')) !!}
                                                        {!! Form::close() !!}
                                                    @else
                                                        <div class="btn btn-red btn-sm">Returned</div>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>

                                        @break
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script src="/js/panel.receive.js"></script>
@stop