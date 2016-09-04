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
        <h3 class="no-margin">Library Receive Report</h3>
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
                    <th>Book Title</th>
                    <th colspan="2">Loaned By</th>
                    <th width="10%">Date Loaned</th>
                    <th width="10%">Date Received</th>
                    <th width="10%">Penalty</th>
                </tr>
            </thead>
            <tbody>
                @if($receives)
                    <?php $totalPenalty = 0; $unreturned = 0; $returned = 0; ?>
                    @foreach($receives as $receive)
                        <tr>
                            <td class="text-center">{{ $receive->Material_Call_Number }}</td>
                            <td class="text-center">{{ $receive->Material_Title }}</td>
                            <td class="text-center" width="10%">{{ $receive->Account_Username }}</td>
                            <td class="text-center" width="10%">
                                @if($receive->Account_Type == 'Faculty')
                                    @if(strlen($receive->Faculty_Middle_Name) > 1)
                                        {{ $receive->Faculty_First_Name . ' ' . substr($receive->Faculty_Middle_Name, 0, 1) . '. ' . $receive->Faculty_Last_Name }}
                                    @else
                                        {{ $receive->Faculty_First_Name . ' ' . $receive->Faculty_Last_Name }}
                                    @endif
                                @elseif($receive->Account_Type == 'Librarian')
                                    @if(strlen($receive->Librarian_Middle_Name) > 1)
                                        {{ $receive->Librarian_First_Name . ' ' . substr($receive->Librarian_Middle_Name, 0, 1) . '. ' . $receive->Librarian_Last_Name }}
                                    @else
                                        {{ $receive->Librarian_First_Name . ' ' . $receive->Librarian_Last_Name }}
                                    @endif
                                @elseif($receive->Account_Type == 'Student')
                                    @if(strlen($receive->Student_Middle_Name) > 1)
                                        {{ $receive->Student_First_Name . ' ' . substr($receive->Student_Middle_Name, 0, 1) . '. ' . $receive->Student_Last_Name }}
                                    @else
                                        {{ $receive->Student_First_Name . ' ' . $receive->Student_Last_Name }}
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">{{ date('F d, Y', strtotime($receive->Loan_Date_Stamp)) }}</td>
                            @if($receive->Loan_Status == 'active')
                                <?php $unreturned++; ?>
                                <td class="text-center" colspan="2">Not yet returned</td>
                            @else
                                <?php $returned++; ?>
                                <td class="text-center">
                                    @if(isset($receive->Receive_Date_Stamp))
                                        {{ date('F d, Y', strtotime($receive->Receive_Date_Stamp)) }}
                                    @endif
                                </td>
                                <td class="text-center">Php {{ $receive->Penalty }}</td>
                            @endif
                        </tr>
                        <?php
                            if(isset($receive->Penalty) && $receive->Penalty != '') {
                                $totalPenalty += $receive->Penalty;
                            }
                        ?>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="6">No data available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
        <div class="text-right">Total number of returned books: {{ $returned }}</div>
        <div class="text-right">Total number of unreturned books: {{ $unreturned }}</div>
        <div class="text-right">Total Penalty Collected: {{ $totalPenalty }}</div>
    </div>
</body>
</html>