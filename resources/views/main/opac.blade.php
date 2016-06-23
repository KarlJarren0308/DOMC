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
                <a href="{{ route('main.getOpac') }}" class="navbar-element active">OPAC</a>
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
        <div class="banner">Online Public Access Catalog</div>
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
        <table id="materials-table" class="u-full-width">
            <thead>
                <tr>
                    <th>Call Number</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Author(s)</th>
                    <th>Available Copies</th>
                    <th width="15%"></th>
                </tr>
            </thead>
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
                        <td class="text-center">
                            <?php $reserved_count = 0; $loaned_count = 0; ?>
                            @foreach($reserved_materials as $reserved_material)
                                @if($reserved_material->Material_ID == $material->Material_ID)
                                    <?php $reserved_count++; ?>
                                @endif
                            @endforeach
                            @foreach($loaned_materials as $loaned_material)
                                @if($loaned_material->Material_ID == $material->Material_ID)
                                    <?php $loaned_count++; ?>
                                @endif
                            @endforeach
                            <?php $newMaterialCount = $material->Material_Copies - $reserved_count - $loaned_count; ?>
                            {{ ($newMaterialCount > 0 ? $newMaterialCount : 0) }}
                        </td>
                        <td class="text-center">
                            @if(strlen(session()->has('username')))
                                <?php $isReserved = false; ?>
                                @foreach($reservations as $reservation)
                                    @if($reservation->Material_ID == $material->Material_ID)
                                        <?php $isReserved = true; ?>
                                        @break
                                    @endif
                                @endforeach

                                @if($isReserved)
                                    <a class="btn btn-red btn-sm">Already Reserved</a>
                                @else
                                    @if($on_reserved < $reservation_limit)
                                        @if($newMaterialCount > 0)
                                            <a href="{{ route('main.getReserve', $material->Material_ID) }}" class="btn btn-orange btn-sm">Reserve</a>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('post_ref')
    <script src="/js/main.opac.js"></script>
@stop