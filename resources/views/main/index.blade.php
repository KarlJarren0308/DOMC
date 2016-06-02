@extends('template')

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <div class="navbar-element logo"><img src="/img/logo.png"></div>
            <div class="navbar-element title">De Ocampo Memorial College</div>
            <div class="u-pull-right">
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
        <div class="row">
            <div class="six columns">
                <div class="banner">Rules and Regulations</div>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab esse maxime porro et, totam veritatis cupiditate odit molestiae veniam molestias commodi sed. Corporis nemo repudiandae aut amet ea rem, dicta!</p>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas pariatur atque tempore doloribus facere ipsa consectetur, quidem. Tempore ducimus qui accusamus id, fugiat repellat repudiandae tempora. Deleniti eligendi, cupiditate tenetur.</p>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus, reprehenderit itaque, id pariatur perferendis totam labore hic tempore facilis laudantium porro recusandae distinctio illum omnis modi non culpa maxime praesentium.</p>
            </div>
            <div class="six columns">
                <div class="banner">Mission</div>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus totam magnam voluptate est accusamus itaque assumenda ipsam, minus expedita labore adipisci consequatur fugiat fugit harum pariatur dolores quia animi dolorem.</p>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi voluptatum maxime, laboriosam non quaerat aspernatur voluptates accusamus a ad illum, facere suscipit autem ducimus, voluptatibus commodi sint laudantium iure! Sint!</p>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum exercitationem repudiandae nisi aut eum neque at dolor veniam vero culpa itaque optio quasi fugit beatae eos, ipsa maxime repellendus delectus.</p>
                <div class="banner">Vision</div>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi quo doloribus soluta ad. Quo dolore sunt quod, rerum corporis ab eligendi placeat blanditiis, dolorem fugiat voluptate, quis eum enim aliquam?</p>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste necessitatibus eveniet veniam. Blanditiis est veniam consequuntur laboriosam dignissimos. Dolorum, possimus harum cupiditate. Voluptatem distinctio obcaecati sequi, laudantium harum, nihil veniam.</p>
                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit quia, minus enim commodi ullam unde officia molestias explicabo sapiente provident inventore ducimus iste architecto nemo maxime fugit excepturi atque dolor.</p>
            </div>
        </div>
    </div>
@stop