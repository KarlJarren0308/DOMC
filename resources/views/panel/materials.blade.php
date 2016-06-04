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
                    <li class="list-group-item active"><a href="{{ route('panel.getManage', 'materials') }}">Manage Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'students') }}">Manage Students</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'faculties') }}">Manage Faculties</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'librarians') }}">Manage Librarians</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'holidays') }}">Manage Holidays</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">Manage Books</div>
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
                <div class="tray text-right">
                    <a href="{{ route('panel.getAdd', $what) }}" class="btn btn-orange">Add</a>
                </div>
                <table id="materials-table" class="u-full-width">
                    <thead>
                        <tr>
                            <th>Call Number</th>
                            <th>Title</th>
                            <th>Author(s)</th>
                            <th>Available Copies</th>
                            <th></th>
                        </tr>
                        <tbody>
                            @foreach($works_materials as $material)
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
                                    <td class="text-center">
                                        <?php $newMaterialCount = $material->Material_Copies - count($reserved_materials->where('Material_ID', $material->Material_ID)->get()) - count($loaned_materials->where('Material_ID', $material->Material_ID)->get()); ?>
                                        {{ ($newMaterialCount > 0 ? $newMaterialCount : 0) }}
                                    </td>
                                    <td class="text-center">
                                        @if(strlen(session()->has('username')))
                                            <a href="{{ route('panel.getEdit', array($what, $material->Material_ID)) }}" class="btn btn-green btn-sm">Edit</a>
                                            <a href="{{ route('panel.getDelete', array($what, $material->Material_ID)) }}" class="btn btn-red btn-sm">Delete</a>
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
    <script src="/js/panel.materials.js"></script>
@stop