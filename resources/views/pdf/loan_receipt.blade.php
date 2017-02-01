<!doctype html>
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

        .row > .one-half.column,
        .row > .one-half.columns {
            display: inline-block;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }

        .overscore {
            border-top: 1px solid black;
            padding: 5px 10px;
            margin: 0 10px 5px 10px;
            height: 75px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="img/logo.png">
        <h1 class="no-margin">De Ocampo Memorial College</h1>
        <h3 class="no-margin">Library Loan Receipt</h3>
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
        <div>Loaned by: <strong>{{ $borrowerName }}</strong></div>
        <div>Date loaned: <strong>{{ date('F d, Y') }}</strong></div>
        <br><br>
        <table class="table full-width gap-bottom">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Accession Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach($addedInfo as $info)
                    <tr>
                        <td>{{ $info['Material_Title'] }}</td>
                        <td>{{ $info['Accession_Number'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
        <div class="row">
            <div class="one-half columns text-center"><div class="overscore">Student</div></div>
            <div class="one-half columns text-center"><div class="overscore">Librarian</div></div>
        </div>
    </div>
</body>
</html>