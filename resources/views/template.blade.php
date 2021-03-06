<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>De Ocampo Memorial College</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway:400,300,600">
    <link rel="stylesheet" href="/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/css/skeleton.css">
    <link rel="stylesheet" href="/css/sweetalert.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/stylesheet.css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/sweetalert.min.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/validations.js"></script>
@yield('pre_ref')
</head>
<body>
@yield('content')
@yield('post_ref')
</body>
</html>