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
        <h3 class="no-margin">Library Book Report</h3>
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
                    <th>Call Number</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Copyright Year</th>
                    <th>Publisher</th>
                    <th>Author(s)</th>
                    <th>Number of Copies</th>
                </tr>
            </thead>
            <tbody>
                @foreach($works_materials as $material)
                    <?php $isFirst = true; ?>
                    <tr>
                        <td class="text-center">{{ $material->Material_Call_Number }}</td>
                        <td class="text-center">{{ $material->Material_Title }}</td>
                        <td class="text-center">{{ $material->Material_ISBN }}</td>
                        <td class="text-center">{{ $material->Material_Copyright_Year }}</td>
                        <td class="text-center">{{ $material->Publisher_Name }}</td>
                        <td class="text-center">
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
                        <td class="text-center">{{ $material->Material_Copies }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>