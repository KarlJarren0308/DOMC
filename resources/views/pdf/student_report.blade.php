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
        <h3 class="no-margin">Library Student Report</h3>
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
        <table class="full-width gap-bottom">
            <tbody>
                <tr>
                    <td class="text-right" width="25%">User I.D. Number:</td>
                    <td style="font-style: italic; font-weight: normal;">{{ $student->Account_Username }}</td>
                </tr>
                <tr>
                    <td class="text-right" width="25%">Name:</td>
                    <td style="font-style: italic; font-weight: normal;">
                        @if(strlen($student['Student_Middle_Name']) > 1)
                            {{ $student->Student_First_Name . ' ' . substr($student->Student_Middle_Name, 0, 1) . '. ' . $student->Student_Last_Name }}
                        @else
                            {{ $student->Student_First_Name . ' ' . $student->Student_Last_Name }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-right" width="25%">Birth Date:</td>
                    <td style="font-style: italic; font-weight: normal;">{{ date('F d, Y', strtotime($student->Student_Birth_Date)) }}</td>
                </tr>
            </tbody>
        </table>
        <table class="table full-width gap-bottom">
            <thead>
                <tr>
                    <th>Call Number</th>
                    <th>Title</th>
                    <th>Copyright Year</th>
                    <th>Date Borrowed</th>
                    <th>Date Returned</th>
                    <th>Penalty</th>
                    <th>Clearance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loans as $loan)
                    <tr>
                        <td>{{ $loan->Material_Call_Number }}</td>
                        <td>{{ $loan->Material_Title }}</td>
                        <td class="text-center">{{ $loan->Material_Copyright_Year }}</td>
                        <td class="text-center">{{ date('F d, Y', strtotime($loan->Loan_Date_Stamp)) }}</td>
                        @if(isset($loan->Receive_ID))
                            <td class="text-center">{{ date('F d, Y', strtotime($loan->Receive_Date_Stamp)) }}</td>
                            <td class="text-center">Php {{ $loan->Penalty }}</td>
                            <td class="text-center">{{ ucfirst($loan->Clearance) }}</td>
                        @else
                            <td class="text-center" colspan="3">Not Yet Returned</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>