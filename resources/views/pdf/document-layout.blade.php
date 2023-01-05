<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $filename }}</title>

    <style type="text/css">
        @page {
            margin-left: 0;
            margin-right: 0;
            margin-top: 3.5cm;
            margin-bottom: {{ isset($document->footer) ? '1.5cm' : '0' }};
        }

        @font-face {
            font-family: 'Inter',
            src: url('Inter.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        * {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-style: normal;
            font-size: 9pt;
            color: #212A33;
        }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { vertical-align: top; padding: 0.1cm 0.25cm; }
        label { display: block; color: #666; margin-bottom: 0.1cm; }

        .total {
            padding: 0.15cm 0.3cm;
            font-weight: 700; 
            vertical-align: middle; 
            font-size: 10pt;
        }
        .total.tax {
            font-size: 9pt;
        }
        .total.grand-total {
            font-size: 12pt;
            background-color: #e0e6ec;
        }
    </style>
</head>
<body>
    @include('atom::pdf.document-header')

    <div style="padding: 1cm 0.6cm;">
        @yield('content')
    </div>

    @yield('footer')
</body>
</html>