<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="referrer" content="no-referrer-when-downgrade">
    <meta name="robots" content="all">
    <title>{{$pageTitle ?? $template}}</title>
    <!-- SEO -->
    <meta name="keywords" content="{{('Brand Website of '.($pageTitle ?? $template) )}}">
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:title" content="{{ $pageTitle ?? $template }}">
    <meta content="400" property="og:image:width">
    <meta content="400" property="og:image:height">
    <meta content="{{ ('An image of '. ($pageTitle ?? $template) ) }}" property="og:image:alt">

    <link rel="shortcut icon" href="{{ ('/media/tenants/'.$tenantID.'/img/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/materialize.min.css">
    <link rel="stylesheet" href="/css/templates.css">
    <link rel="stylesheet" href="{{ strtolower('/css/'.$templateCSS) }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app">
        <go-live-component></go-live-component>
    </div>
    <script src="/js/app.js"></script>
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/js/materialize.min.js"></script>
    <script src="/js/paab.js"></script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v16.0" nonce="WtqDSqzj"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <script async src="//www.instagram.com/embed.js"></script>
    <script async src="https://www.tiktok.com/embed.js"></script>
    
    <script>
        var dropdowns = document.querySelectorAll('.dropdown-trigger')
        for (var i = 0; i < dropdowns.length; i++){
            M.Dropdown.init(dropdowns[i]);
        }
    </script>
</body>
</html>
