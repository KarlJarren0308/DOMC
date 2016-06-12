@extends('template')

@section('content')
    <?php
        function isHoliday($date, $holidays) {
            $date = date('Y-m-d', strtotime($date));

            if(count($holidays) > 0) {
                foreach($holidays as $holiday) {
                    if($holiday->Holiday_Type == 'Suspension') {
                        if($date == date('Y-m-d', strtotime($holiday->Holiday_Date))) {
                            return true;
                        }
                    } else if($holiday->Holiday_Type == 'Regular') {
                        if(date('m-d', strtotime($date)) == date('m-d', strtotime($holiday->Holiday_Type))) {
                            return true;
                        }
                    }
                }

                return false;
            } else {
                return false;
            }
        }

        function isWeekend($date) {
            $date = date('l', strtotime($date));

            if($date == 'Sunday') {
                return true;
            } else if($date == 'Saturday') {
                return true;
            } else {
                return false;
            }
        }

        function nextDay($date) {
            return date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }
    ?>
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
        <div class="banner">Account Information</div>
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
        <div class="text-right gap-bottom">
            <a href="{{ route('main.getChangePassword') }}" class="btn btn-orange btn-sm">Change Password</a>
        </div>
        <table class="u-full-width">
            <tbody>
                <tr>
                    <td class="text-right" width="25%">Username:</td>
                    <td><strong>{{ $my_account_one->Account_Username }}</strong></td>
                </tr>
                <tr>
                    <td class="text-right" width="25%">Name:</td>
                    <td>
                        <strong>
                            @if($my_account_one->Account_Type == 'Faculty')
                                @if(strlen($my_account_two->Faculty_Middle_Name) > 1)
                                    {{ $my_account_two->Faculty_First_Name . ' ' . substr($my_account_two->Faculty_Middle_Name, 0, 1) . '. ' . $my_account_two->Faculty_Last_Name }}
                                @else
                                    {{ $my_account_two->Faculty_First_Name . ' ' . $my_account_two->Faculty_Last_Name }}
                                @endif
                            @elseif($my_account_one->Account_Type == 'Librarian')
                                @if(strlen($my_account_two->Librarian_Middle_Name) > 1)
                                    {{ $my_account_two->Librarian_First_Name . ' ' . substr($my_account_two->Librarian_Middle_Name, 0, 1) . '. ' . $my_account_two->Librarian_Last_Name }}
                                @else
                                    {{ $my_account_two->Librarian_First_Name . ' ' . $my_account_two->Librarian_Last_Name }}
                                @endif
                            @elseif($my_account_one->Account_Type == 'Student')
                                @if(strlen($my_account_two->Student_Middle_Name) > 1)
                                    {{ $my_account_two->Student_First_Name . ' ' . substr($my_account_two->Student_Middle_Name, 0, 1) . '. ' . $my_account_two->Student_Last_Name }}
                                @else
                                    {{ $my_account_two->Student_First_Name . ' ' . $my_account_two->Student_Last_Name }}
                                @endif
                            @endif
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" width="25%">Birthday:</td>
                    <td>
                        <strong>
                            @if($my_account_one->Account_Type == 'Faculty')
                                {{ date('F d, Y', strtotime($my_account_two->Faculty_Birth_Date)) }}
                            @elseif($my_account_one->Account_Type == 'Librarian')
                                {{ date('F d, Y', strtotime($my_account_two->Librarian_Birth_Date)) }}
                            @elseif($my_account_one->Account_Type == 'Student')
                                {{ date('F d, Y', strtotime($my_account_two->Student_Birth_Date)) }}
                            @endif
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" width="25%">Book(s) on Hand:</td>
                    <td><strong>{{ $my_account_one->Account_On_Hand + $on_hand }}</strong></td>
                </tr>
                <tr>
                    <td class="text-right" width="25%">Book(s) Reserved:</td>
                    <td><strong>{{ $on_reserved }}</strong></td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="six columns">
                <div class="banner">Reservation(s)</div>
                <div class="list" style="overflow-y: scroll; max-height: 500px;">
                    @foreach($reservations as $reservation)
                        <div class="list-item">
                            <div class="header">{{ $reservation->Material_Title }}</div>
                            <div class="body">  
                                <div class="text-justify">Published by: <em>{{ ($reservation->Publisher_Name != '' ? $reservation->Publisher_Name : '[None]') }}</em></div>
                                <div>
                                    Author(s):
                                    <ul class="bullet-list">
                                        @foreach($works_authors as $workAuthor)
                                            @if($workAuthor->Material_ID == $reservation->Material_ID)
                                                @if(strlen($workAuthor->Author_Middle_Name) > 1)
                                                    <li><em>{{ $workAuthor->Author_First_Name . ' ' . substr($workAuthor->Author_Middle_Name, 0, 1) . '. ' . $workAuthor->Author_Last_Name }}</em></li>
                                                @else
                                                    <li><em>{{ $workAuthor->Author_First_Name . ' ' . $workAuthor->Author_Last_Name }}</em></li>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="footer">
                                @if($reservation->Reservation_Status == 'active')
                                    <div class="gap-bottom gap-left gap-right">Expires in <span class="countdown" data-var-id="{{ $reservation->Reservation_ID }}" data-var-start="{{ strtotime('+1 day', strtotime($reservation->Reservation_Date_Stamp . ' ' . $reservation->Reservation_Time_Stamp)) }}" data-var-end="{{ strtotime(date('Y-m-d H:i:s')) }}"></span></div>
                                    {!! Form::open(array('route' => array('main.postCancelReservation'))) !!}
                                    {!! Form::hidden('arg0', 'a8affc088cbca89fa20dbd98c91362e4') !!}
                                    {!! Form::hidden('arg1', $reservation->Reservation_ID) !!}
                                    {!! Form::submit('Cancel Reservation', array('class' => 'btn btn-red btn-sm u-pull-right')) !!}
                                    {!! Form::close() !!}
                                @elseif($reservation->Reservation_Status == 'loaned')
                                    <div class="text-right">Book Loaned</div>
                                @else
                                    <div class="text-right">Reservation Cancelled</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="six columns">
                <div class="banner">Loaned Book(s)</div>
                <div class="list" style="overflow-y: scroll; max-height: 500px;">
                    @foreach($loans as $loan)
                        <div class="list-item">
                            <div class="header">{{ $loan->Material_Title }}</div>
                            <div class="body">
                                <div class="text-justify">Published by: <em>{{ ($loan->Publisher_Name != '' ? $loan->Publisher_Name : '[None]') }}</em></div>
                                <div>
                                    Author(s):
                                    <ul class="bullet-list">
                                        @foreach($works_authors as $workAuthor)
                                            @if($workAuthor->Material_ID == $loan->Material_ID)
                                                @if(strlen($workAuthor->Author_Middle_Name) > 1)
                                                    <li><em>{{ $workAuthor->Author_First_Name . ' ' . substr($workAuthor->Author_Middle_Name, 0, 1) . '. ' . $workAuthor->Author_Last_Name }}</em></li>
                                                @else
                                                    <li><em>{{ $workAuthor->Author_First_Name . ' ' . $workAuthor->Author_Last_Name }}</em></li>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <?php
                                // Penalty Computation
                                $dateLoaned = $loan->Loan_Date_Stamp . ' ' . $loan->Loan_Time_Stamp;
                                $dayEnd = date('Y-m-d H:i:s', strtotime('+' . $start_penalty_after . ' days', strtotime($dateLoaned)));
                                $dayStart = strtotime($dateLoaned);
                                $graceDays = ceil((strtotime($dayEnd) - $dayStart) / 86400);
                                $i = 1;

                                while($i <= $graceDays) {
                                    $markedDate = date('Y-m-d H:i:s', strtotime('+' . $i . ' days', strtotime($dateLoaned)));

                                    if(isWeekend($markedDate)) {
                                        $graceDays++;
                                        $dayEnd = nextDay($dayEnd);
                                    } else {
                                        if(isHoliday($markedDate, $holidays)) {
                                            $graceDays++;
                                            $dayEnd = nextDay($dayEnd);
                                        }
                                    }

                                    $i++;
                                }

                                $newDayEnd = $dayEnd;
                                $newGraceDays = ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($newDayEnd)) / 86400);
                                $j = 1;

                                while($j <= $newGraceDays) {
                                    $markedDate = date('Y-m-d H:i:s', strtotime('+' . $j . ' days', strtotime($newDayEnd)));

                                    if(isWeekend($markedDate)) {
                                        $newGraceDays++;
                                        $newDayEnd = nextDay($newDayEnd);
                                    } else {
                                        if(isHoliday($markedDate, $holidays)) {
                                            $newGraceDays++;
                                            $newDayEnd = nextDay($newDayEnd);
                                        }
                                    }

                                    $j++;
                                }

                                $totalPenalty = floor((strtotime(date('Y-m-d H:i:s')) - strtotime($newDayEnd)) / 86400) * (double) $per_day_penalty;
                            ?>
                            <div class="footer">
                                @if($loan->Loan_Status == 'active')
                                    <div class="u-pull-left">Penalty: <strong>&#8369;{{ ($totalPenalty > 0 ? $totalPenalty : 0) }}.00</strong></div>
                                    <div class="text-right">Not yet returned</div>
                                @else
                                    <div class="text-right">Book Returned</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('post_ref')
    <script>
        var url = '<?php echo route("main.postCancelReservation"); ?>';
        var token = '<?php echo csrf_token(); ?>';
    </script>
    <script src="/js/main.account_information.js"></script>
@stop