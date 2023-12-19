<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="referrer" content="no-referrer-when-downgrade">
    <meta name="robots" content="all">
    <title>{{ $metaData->title ?? $brand_name ?? 'Preview' }}</title>
    <!-- SEO -->
    <meta name="keywords" content="{{('Brand Website of '.($brand_name ?? 'Preview') )}}">
    <meta name="description" content="{{ $metaData->description ?? 'This is a description of what our brand is about and what we do' }}">
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $metaData->title ?? 'This is a description of what our brand is about and what we do' }}">
    <meta property="og:url" content="{{ url()->full() }}">
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{ $metaData->description ?? 'This is a description of what our brand is about and what we do' }}">
    <meta property="og:image" content="{{ url($metaData->image ?? '')  }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta content="{{ ('An image of '. ($brand_name ?? 'Preview') ) }}" property="og:image:alt">

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $metaData->title ?? 'This is a title of what our brand is about and what we do' }}" />
    <meta name="twitter:description" content="{{ $metaData->description ?? 'This is a description of what our brand is about and what we do' }}" />
    <meta name="twitter:url" content="{{ url()->full() }}" />
    <meta name="twitter:image" content="{{ url($metaData->image ?? '') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://cdn.jsdelivr.net/npm/material-icons@1.13.8/iconfont/material-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ global_asset('css/zebraline.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Jost:wght@100;200;300;400;500;600;700;800;900&family=Lexend:wght@100;200;300;400;500;600;700;800;900&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Public+Sans:wght@100;200;300;400;500;600;700;800;900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app" data-renederer-prop="{{$storeTypeId}}">
        <renderer-website-component branddesc="{{$brandDesc ?? ''}}"  email="{{$email}}" metaData="{{$metaData ?? ''}}" footerTempId="{{$footer_tempId}}" 
            navbarTempId="{{$navbar_tempId}}" heroTempId="{{$hero_tempId}}" categoryTempId="{{$category_tempId}}" featuredPrdTempId="{{$featured_tempId}}"
            blogTempId="{{$blog_tempId}}" offerTempId="{{$offer_tempId}}" sellingpointTempId="{{$sellin_point_tempId}}" reviewTempId="{{$review_tempId}}"
            storetypeId="{{$storeTypeId}}" themecolor="{{$theme_color ?? ''}}" brandName="{{$brand_name ?? ''}}">
        </renderer-website-component>
    </div>
    @vite('resources/js/app.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ global_asset('js/zebraline.js') }}"></script>
    
    <script>
        var dropdowns = document.querySelectorAll('.dropdown-trigger')
        for (var i = 0; i < dropdowns.length; i++){
            M.Dropdown.init(dropdowns[i]);
        }
        window.environment = "{{ config('app.env') }}";
    </script>
    <script src="https://openexchangerates.github.io/money.js/money.min.js"></script>
</body>
</html>
