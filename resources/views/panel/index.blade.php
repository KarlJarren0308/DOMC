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
                    <li class="list-group-item active"><a href="{{ route('panel.getIndex') }}">Home</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getLoan') }}">Loan Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReserved') }}">Reserved Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReceive') }}">Receive Book(s)</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'materials') }}">Manage Books</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'authors') }}">Manage Authors</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'publishers') }}">Manage Publishers</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'students') }}">Manage Students</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'faculties') }}">Manage Faculties</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'librarians') }}">Manage Librarians</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getManage', 'holidays') }}">Manage Holidays</a></li>
                    <li class="list-group-item"><a href="{{ route('panel.getReports') }}">Library Reports</a></li>
                </ul>
            </div>
            <div class="nine columns">
                <div class="banner">What's New?</div>
                <div class="row" style="margin-bottom: 15px;">
                    <div class="four columns">
                        <a href="{{ route('panel.getReserved') }}" class="panel">
                            <div class="panel-header">
                                <div>
                                    <h1 id="r-count" class="no-margin">0</h1>
                                </div>
                                <div>Reserved Book(s)</div>
                            </div>
                        </a>
                    </div>
                    <div class="four columns">
                        <a href="{{ route('panel.getReceive') }}" class="panel">
                            <div class="panel-header">
                                <div>
                                    <h1 id="l-count" class="no-margin">0</h1>
                                </div>
                                <div>Loaned Book(s)</div>
                            </div>
                        </a>
                    </div>
                    <div class="four columns">
                        <a href="{{ route('panel.getReceive') }}" class="panel">
                            <div class="panel-header">
                                <div>
                                    <h1 id="v-count" class="no-margin">0</h1>
                                </div>
                                <div>Visitor(s)</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="six columns">
                        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta reiciendis illum possimus et ducimus quia sed, dolorum id cumque rerum, fuga voluptatem fugiat quidem mollitia eos vel nisi voluptatum corrupti.</p>
                    </div>
                    <div class="six columns">
                        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae vitae quas, praesentium hic pariatur est voluptatibus suscipit vel tempora necessitatibus animi, ratione tempore modi. Veritatis, dignissimos nostrum iste ab non!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal">
        <div class="modal-container">
            <div class="modal-header"></div>
            <div class="modal-body">
                <div class="text-center gap-top gap-bottom">
                    <span class="fa fa-spinner fa-4x fa-pulse"></span>
                    <div class="gap-top">
                        Initializing... Please Wait...
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script>
        var url = '<?php echo route("panel.postInitialize"); ?>';
        var token = '<?php echo md5(base64_decode("ZG9tYw==")) . base64_encode(csrf_token()) . md5(base64_decode("bGlicmFyeQ==")); ?>';
    </script>
    <script src="/js/panel.index.js"></script>
@stop