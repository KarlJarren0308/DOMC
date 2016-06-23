@extends('template')

@section('pre_ref')
    <style>
        .terms {
            overflow-y: scroll;
            padding-right: 5px;
            margin-bottom: 25px;
            max-height: 500px;
        }

        .terms > p:last-child {
            margin-bottom: 0;
        }
    </style>
@stop

@section('content')
    <div class="navbar fixed-top shadow">
        <div class="navbar-content">
            <a href="{{ route('main.getIndex') }}" class="navbar-element-brand">
                <div class="navbar-element logo"><img src="/img/logo.png"></div>
                <div class="navbar-element title">De Ocampo Memorial College</div>
            </a>
            <div class="u-pull-right">
                <a href="{{ route('main.getAbout') }}" class="navbar-element active">About Us</a>
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
        <div class="row">
            <div class="six columns">
                <div class="banner">School's History</div>
                <div class="terms">
                    <p class="text-justify">The year 1913 marked the beginning of the De Ocampo Memorial College. It was then named Philippine Dental College- the first Dental College recognized by the Philippine government and authorized to confer the degree of Doctor of Dental Surgery (DDS) in March 25, 1916 and Doctor of Dental Medicine (DMD) in December 19, 1932. The Graduate in Nursing Course opened in 1954 leading to a graduate in Nursing or (GN) as a three (3) year program.</p>
                    <p class="text-justify">In the 70’s Medical Secretarial, Health Aid, Dental Laboratory Technician, Opto. Laboratory Technician, Tourism and Culinary Arts were offered. The courses were ladderized to accommodate students who opted for short term courses. In 1974, the Nursing Course was transformed into a five year degree program with the title of Bachelor of Science in Nursing.</p>
                    <p class="text-justify">In the 80’s, De Ocampo Memorial School was renamed De Ocampo Memorial College. To enhance its educational program and to address the needs of society, the newly renamed institution introduced courses in Midwifery, Hotel and Restaurant Management, Dental Hygiene and the Caregiving (formerly 6 months Nanny Course). It was already during this dynamic decade that the five (5) year course in Nursing was reduced to a four (4) year curriculum which exists to this date.</p>
                    <p class="text-justify">Under the dynamic leadership of Dr. Antonio B. De Ocampo, the school expanded and improved. New courses were introduced to meet the increasing educational demands of the country. With this, De Ocampo Memorial College came into being.</p>
                    <p class="text-justify">There was no let-up! In the 90’s, B.S Physical Therapy, B.S, HRM, B.S. Secretarial Administration Major in Computer, Guest Relation Officer Management, and Dietetics Technician were further added to the existing courses. For enrichment, Computer Education and Nippongo were integrated in all courses.</p>
                    <p class="text-justify">The De Ocampo Memorial College campus is located along Ramon Magsaysay Boulevard, Sta. Mesa, Manila (near L.R.T. 2 Pureza Station). This campus houses the Colleges of Dentistry, Medical Technology, Nursing, Liberal Arts, Education, Hotel and Restaurant Management, Physical Therapy, Office Administration, School of Midwifery, Dental Hygiene, Dental Laboratory Technology Services, Healthcare Services and Caregiving Services.</p>
                    <p class="text-justify">After the demise of Dr. Antonio B. De Ocampo in 2004, who served as President for more than three decades, the mantle of responsibility was shared by his two children who have been long trained to take over: Dr. Vicente Antonio A. De Ocampo – Chairman of the Board and concurrent President and Dr. Maria Victoria De Ocampo Lantin as Executive Vice President. Continuing the vision of the founding fathers to provide innovative and relevant programs to meet the times while maintaining reasonable school rates, the new and able leaders immediately worked for the addition of the Bachelor of Science in Psychology and the Bachelor of Secondary Education. The most remarkable change was the recent upgrading of the laboratory facilities of the much in demand Dental Laboratory Technology Course formerly known as Dental Technician Course. The De Ocampo Memorial College became the first school therefore to be accredited by TESDA to offer this course.</p>
                    <p class="text-justify">The future is that the present administration remains constantly open for the development of new courses and programs needed by the local or international communities.</p>
                    <p class="text-justify">The De Ocampo Memorial College has a hospital serving various specialties, housing reputable doctors and is also conveniently used by the students for their practicum training. President De Ocampo and Vice President Lantin extensively focused on modernizing and upgrading the hospital facilities. In the year 2009, the De Ocampo Memorial Medical Center completed its major facelift and formally opened its doors and has since been patronized by the top HMO’s in the country.</p>
                    <p class="text-justify">The College is committed to international interchange as the youthful President De Ocampo renewed its sister tie up with the Chungshan Medical University of Taiwan. The President also personally looks after the varsity teams of the school to ensure commitment to sports. The Basketball team usually finishes among the top teams in various organized leagues.</p>
                    <p class="text-justify">Noteworthy is the scholarship offer of the college which genuinely grants free full tuition fees to deserving valedictorians of high school students who are able to maintain a certain average grade are rewarded accordingly with and abroad.</p>
                    <p class="text-justify">Indeed, the legacy of the De Ocampos to be always an educational option catering to the rich or poor, to the working students, to foreigners, and to all those who need a “home” is now handled by the 4th generation of De Ocampos dedicated to continue beyond a century of academic excellence.</p>
                </div>
            </div>
            <div class="six columns">
                <div class="banner">Vision</div>
                <div class="terms">
                    <p class="text-justify">Our school vision is expressed in the Latin words;Veritas, Virtus, Sapienta as contained in our school logo. Thus, this school is dedicated to the sincere search of truth; the promotion of science and the appreciation of all that are virtuous.</p>
                </div>
                <div class="banner">Mission</div>
                <div class="terms">
                    <p class="text-justify">The De Ocampo Memorial College endeavors to mold the students into well-integrated, productive and serviceable citizens.</p>
                    <p class="text-justify">Towards this end, it imbues the students with moral values, a keen sense of social awareness and helps them maximize their potentials, thus preparing them to commit to the service of God, Country and Fellowmen.</p>
                </div>
            </div>
        </div>
    </div>
@stop