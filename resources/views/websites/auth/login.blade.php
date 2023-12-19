<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="keywords" content="Website builder, Physician Website Builder, Personal Website Builder, Physician Brand Website">
    <meta name="description" content="Zebraline - A dose of your medical story - building a brand around your medical career">
    <meta name="referrer" content="no-referrer-when-downgrade">
    <meta name="robots" content="all">
    <meta content="Zebraline" property="og:site_name">
    <meta content="website" property="og:type">
    <meta content="https://zebraline.ai/" property="og:url">
    <meta content="Zebraline - is a fast-growing Peopl-as-a-Brand solution that helps physician own and easy to setup, easy to manage and very financial comfortable web presence for showcasing ther thriving careers." property="og:title">
    <meta content="Zebraline - A dose of your medical story - building a brand around your medical career. We provide medical practitioners with a web presence to promote and showcase their thriving career." property="og:description">
    <meta content="https://zebraline.ai/media/img/zebralineSlogan.png" property="og:image">
    <meta content="400" property="og:image:width">
    <meta content="400" property="og:image:height">
    <meta content="An image of the Zebraline logo" property="og:image:alt">


    <title>{{env('APP_NAME')}}: Zebraline - Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://cdn.jsdelivr.net/npm/material-icons@1.13.8/iconfont/material-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ global_asset('css/zebraline.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
</head>
<body>
    <div id="app">
        <tenant-signin-component themeColor="{{$theme_color}}" brandName="{{$brand_name}}"></tenant-signin-component>
    </div>

    <script src="/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ global_asset('js/zebraline.js') }}"></script>
</body>
</html>