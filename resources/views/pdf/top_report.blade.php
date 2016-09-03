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
        <h3 class="no-margin">Library Top Report</h3>
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
        <br>
        <h3 class="no-margin text-center gap-bottom">Top Borrower{{ (count($borrowers) > 1 ? 's' : '') }}</h3>
        <table class="table full-width gap-bottom">
            <thead>
                <tr>
                    <th width="10%"></th>
                    <th width="50%">Borrower's Name</th>
                    <th width="20%">Membership</th>
                    <th width="20%">Count</th>
                </tr>
            </thead>
            <tbody>
                @if($borrowers)
                    <?php $ranking = 1; ?>
                    @foreach($borrowers as $borrower)
                        <tr>
                            <td class="text-center">{{ $ranking }}</td>
                            <td>
                                @if($borrower->Account_Type == 'Faculty')
                                    @if(strlen($borrower->Faculty_Middle_Name) > 1)
                                        {{ $borrower->Faculty_First_Name . ' ' . substr($borrower->Faculty_Middle_Name, 0, 1) . '. ' . $borrower->Faculty_Last_Name }}
                                    @else
                                        {{ $borrower->Faculty_First_Name . ' ' . $borrower->Faculty_Last_Name }}
                                    @endif
                                @elseif($borrower->Account_Type == 'Librarian')
                                    @if(strlen($borrower->Librarian_Middle_Name) > 1)
                                        {{ $borrower->Librarian_First_Name . ' ' . substr($borrower->Librarian_Middle_Name, 0, 1) . '. ' . $borrower->Librarian_Last_Name }}
                                    @else
                                        {{ $borrower->Librarian_First_Name . ' ' . $borrower->Librarian_Last_Name }}
                                    @endif
                                @elseif($borrower->Account_Type == 'Student')
                                    @if(strlen($borrower->Student_Middle_Name) > 1)
                                        {{ $borrower->Student_First_Name . ' ' . substr($borrower->Student_Middle_Name, 0, 1) . '. ' . $borrower->Student_Last_Name }}
                                    @else
                                        {{ $borrower->Student_First_Name . ' ' . $borrower->Student_Last_Name }}
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">{{ $borrower->Account_Type }}</td>
                            <td class="text-center">{{ $borrower->Row_Count }}</td>
                        </tr>
                        @if($ranking == 10)
                            @break
                        @else
                            <?php $ranking++; ?>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="6">No data available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
        <h3 class="no-margin text-center gap-bottom">Most Borrowed Book{{ (count($materials) > 1 ? 's' : '') }}</h3>
        <table class="table full-width gap-bottom">
            <thead>
                <tr>
                    <th width="10%"></th>
                    <th width="50%">Book Title</th>
                    <th width="20%">Collection Type</th>
                    <th width="20%">Count</th>
                </tr>
            </thead>
            <tbody>
                @if($materials)
                    <?php $ranking = 1; ?>
                    @foreach($materials as $material)
                        <tr>
                            <td class="text-center">{{ $ranking }}</td>
                            <td>{{ $material->Material_Title }}</td>
                            <td class="text-center">{{ $material->Material_Collection_Type }}</td>
                            <td class="text-center">{{ $material->Row_Count }}</td>
                        </tr>
                        @if($ranking == 10)
                            @break
                        @else
                            <?php $ranking++; ?>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="6">No data available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>