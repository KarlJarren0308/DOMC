@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <div class="navbar-element title">De Ocampo Memorial College</div>
            <div class="u-pull-right">
                <a href="{{ route('main.getOpac') }}" class="navbar-element active">OPAC</a>
                @if(session()->has('username'))
                    @if(session()->get('account_type') == 'Librarian')
                        <a href="{{ route('panel.getIndex') }}" class="navbar-element">Control Panel</a>
                    @endif

                    <a href="" class="navbar-element">
                        @if(strlen(session()->get('middle_name')) > 1)
                            {{ session()->get('first_name') . ' ' . substr(session()->get('middle_name'), 0, 1) . '. ' . session()->get('last_name') }}
                        @else
                            {{ session()->get('first_name') . ' ' . session()->get('last_name') }}
                        @endif
                    </a>
                @else
                    <a href="{{ route('main.getLogin') }}" class="navbar-element">Login</a>
                @endif
            </div>
        </div>
    </div>
    <div id="main-container" class="container">
        <table id="materials-table" class="u-full-width">
            <thead>
                <tr>
                    <th>Call Number</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Author(s)</th>
                    <th></th>
                </tr>
                <tbody>
                    @foreach($works_materials as $material)
                        <?php $isFirst = true; ?>
                        <tr>
                            <td>{{ $material->Material_Call_Number }}</td>
                            <td>{{ $material->Material_Title }}</td>
                            <td>{{ $material->Material_ISBN }}</td>
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
                                @if(strlen(session()->has('username')))
                                    <a href="" class="btn-orange btn-sm">Reserve</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </thead>
        </table>
    </div>
@stop

@section('post_ref')
    <script src="/js/main.opac.js"></script>
@stop