<!DOCTYPE html>
<html lang="en">
<head>
    <title>De Ocampo Memorial College</title>
    <style>
        * {
            font-family: 'Helvetica', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: thin;
        }

        .pagenum:before {
            content: counter(page);
        }

        .table {
            border: 1px solid #2c8700;
            border-spacing: none;
        }

        .table thead > tr {
            background: #2c8700;
            color: white;
            font-size: 0.85em;
        }

        .table tbody > tr:nth-child(even) {
            background: white;
        }

        .table tbody > tr:nth-child(odd) {
            background: #eee;
        }

        .table th, .table td {
            padding: 5px 10px;
            box-sizing: border-box;
        }

        .table td {
            font-size: 0.75em;
        }

        .header {
            margin-bottom: 50px;
            text-align: center;
        }

        .footer {
            border-top: 1px solid #222;
            color: #777;
            font-size: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .no-margin {
            margin: 0;
        }

        .full-width {
            width: 100%;
        }

        .gap-top {
            margin-top: 5px;
        }

        .gap-bottom {
            margin-bottom: 5px;
        }

        .gap-left {
            margin-left: 5px;
        }

        .gap-right {
            margin-right: 5px;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .logo {
            height: 65px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="img/logo.png">
        <h1 class="no-margin">De Ocampo Memorial College</h1>
        <h3 class="no-margin">Library Reservation Report</h3>
    </div>
    <div class="footer">
        <script type="text/php">
            if(isset($pdf)) {
                $font = Font_Metrics::get_font('helvetica', '');
                $pageText = 'Page {PAGE_NUM} of {PAGE_COUNT}';
                $x = $pdf->get_width() - Font_Metrics::get_text_width($pageText, $font, 7) + 52;
                $y = $pdf->get_height() - 32;
                $pdf->page_text($x, $y, $pageText, $font, 7, array(.467, .467, .467));
                $pdf->page_text(37, $y, 'This is a system generated report.', $font, 7, array(.467, .467, .467));
            }
        </script>
    </div>
    <div class="body">
        <h6 class="no-margin text-right gap-bottom">Date Range: {{ date('F d, Y', strtotime($from)) }} - {{ date('F d, Y', strtotime($to)) }}</h6>
        <table class="table full-width gap-bottom">
            <thead>
                <tr>
                    <th width="15%">Call Number</th>
                    <th width="40%">Book Title</th>
                    <th width="15%">Reserved By</th>
                    <th width="15%">Date Reserved</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if($reservations)
                    <?php $lCount = 0; $cCount = 0; ?>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td class="text-center">{{ $reservation->Material_Call_Number }}</td>
                            <td class="text-center">{{ $reservation->Material_Title }}</td>
                            <td class="text-center">
                                @if($reservation->Account_Type == 'Faculty')
                                    @if(strlen($reservation->Faculty_Middle_Name) > 1)
                                        {{ $reservation->Faculty_First_Name . ' ' . substr($reservation->Faculty_Middle_Name, 0, 1) . '. ' . $reservation->Faculty_Last_Name }}
                                    @else
                                        {{ $reservation->Faculty_First_Name . ' ' . $reservation->Faculty_Last_Name }}
                                    @endif
                                @elseif($reservation->Account_Type == 'Librarian')
                                    @if(strlen($reservation->Librarian_Middle_Name) > 1)
                                        {{ $reservation->Librarian_First_Name . ' ' . substr($reservation->Librarian_Middle_Name, 0, 1) . '. ' . $reservation->Librarian_Last_Name }}
                                    @else
                                        {{ $reservation->Librarian_First_Name . ' ' . $reservation->Librarian_Last_Name }}
                                    @endif
                                @elseif($reservation->Account_Type == 'Student')
                                    @if(strlen($reservation->Student_Middle_Name) > 1)
                                        {{ $reservation->Student_First_Name . ' ' . substr($reservation->Student_Middle_Name, 0, 1) . '. ' . $reservation->Student_Last_Name }}
                                    @else
                                        {{ $reservation->Student_First_Name . ' ' . $reservation->Student_Last_Name }}
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">{{ date('F d, Y', strtotime($reservation->Reservation_Date_Stamp)) }}</td>
                            <td class="text-center">
                                @if($reservation->Reservation_Status == 'active')
                                    {{ 'Currently Reserved' }}
                                @elseif($reservation->Reservation_Status == 'loaned')
                                    {{ 'Loaned' }}
                                    <?php $lCount += 1; ?>
                                @else
                                    {{ 'Cancelled' }}
                                    <?php $cCount += 1; ?>
                                @endif
                            </td>
                        </tr>
                        <?php
                            if(isset($reservation->Penalty) && $reservation->Penalty != '') {
                                $totalPenalty += $reservation->Penalty;
                            }
                        ?>
                    @endforeach
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                @else
                    <tr>
                        <td class="text-center" colspan="5">No data available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
        <div class="text-right">Total copies reserved: {{ count($reservations) }}</div>
        <div class="text-right">Total copies loaned from reservation: {{ $lCount }}</div>
        <div class="text-right">Total copies cancelled: {{ $cCount }}</div>
    </div>
</body>
</html>