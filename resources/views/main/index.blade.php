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
                @if(session()->has('username'))
                    @if(session()->get('account_type') == 'Librarian')
                        <a href="{{ route('panel.getIndex') }}" class="navbar-element">Control Panel</a>
                    @endif
                    
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
                @else
                    <a href="{{ route('main.getLogin') }}" class="navbar-element">Login</a>
                @endif
            </div>
        </div>
    </div>
    <div id="main-container" class="container">
        <img src="/img/banner.jpg" style="margin-bottom: 25px; width: 100%;">
        <div class="banner text-center">Library System</div>
        <h3 class="no-margin">Bulletin Board</h3>
        <hr>
        <div class="row">
            <div class="four columns">
                @if($material != null)
                    <div class="sticky-note yellow">
                        <div class="sticky-note-header">Good News!</div>
                        <div class="sticky-note-body">
                            <p>A {{ strtolower($material->Material_Collection_Type) }} titled <strong>{{ $material->Material_Title }}</strong> has arrived. It's is now ready to be loaned.</p>
                        </div>
                    </div>
                @endif
                <div class="sticky-note green"></div>
                <div class="sticky-note red"></div>
            </div>
            <div class="four columns">
                @if($most_borrowed_material != null)
                    <div class="sticky-note red">
                        <div class="sticky-note-header">Most Borrowed Book</div>
                        <div class="sticky-note-body">
                            <p>The most borrowed book is the <strong>{{ $most_borrowed_material->Material_Title }}</strong>.</p>
                        </div>
                    </div>
                @endif
                <div class="sticky-note yellow"></div>
                <div class="sticky-note green"></div>
            </div>
            <div class="four columns">
                @if($borrower != null)
                    <div class="sticky-note green">
                        <div class="sticky-note-header">Top Borrower</div>
                        <div class="sticky-note-body">
                            @if($borrower->Account_Type == 'Faculty')
                                @if(strlen($borrower->Faculty_Middle_Name) > 1)
                                    <?php $name = $borrower->Faculty_First_Name . ' ' . substr($borrower->Faculty_Middle_Name, 0, 1) . '. ' . $borrower->Faculty_Last_Name; ?>
                                @else
                                    <?php $name = $borrower->Faculty_First_Name . ' ' . $borrower->Faculty_Last_Name; ?>
                                @endif
                            @elseif($borrower->Account_Type == 'Librarian')
                                @if(strlen($borrower->Librarian_Middle_Name) > 1)
                                    <?php $name = $borrower->Librarian_First_Name . ' ' . substr($borrower->Librarian_Middle_Name, 0, 1) . '. ' . $borrower->Librarian_Last_Name; ?>
                                @else
                                    <?php $name = $borrower->Librarian_First_Name . ' ' . $borrower->Librarian_Last_Name; ?>
                                @endif
                            @elseif($borrower->Account_Type == 'Student')
                                @if(strlen($borrower->Student_Middle_Name) > 1)
                                    <?php $name = $borrower->Student_First_Name . ' ' . substr($borrower->Student_Middle_Name, 0, 1) . '. ' . $borrower->Student_Last_Name; ?>
                                @else
                                    <?php $name = $borrower->Student_First_Name . ' ' . $borrower->Student_Last_Name; ?>
                                @endif
                            @endif
                            <p>Congratulations to <strong>{{ $name }}</strong> for being our top borrower.</p>
                        </div>
                    </div>
                @endif
                <div class="sticky-note red"></div>
                <div class="sticky-note yellow"></div>
            </div>
        </div>
    </div>
@stop