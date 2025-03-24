<html>

<head>
    <title>{{ $title }}</title>
    <base href="{{ $home . $version . '/' }}">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="application-name" content="{{ Util::config('app_name') }}">
    <meta name="apple-mobile-web-app-title" content="{{ Util::config('app_name') }}">
    <meta http-equiv="content-language" content="{{ Util::config('lang') }}">
    <meta name="author" content="{{ Util::config('app_name') }}">
    <meta name="copyright" content="{{ Util::config('app_name') }}">
    <meta name="creator" content="{{ Util::config('app_name') }}">
    <!-- opengraph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $home . $version . '/' }}">
    <meta property="og:title" content="{{ Util::config('app_name') }}">
    <meta property="og:description" content="{{ Util::config('app_name') }}">
    <meta property="og:site_name" content="{{ Util::config('app_name') }}">
    <meta property="og:image" itemprop="image" content="/og-image.png">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="315">
    <meta property="og:image:alt" content="{{ Util::config('app_name') }}">
    <meta property="og:image:type" content="image/svg">
    <meta property="og:image:secure_url" content="{{ Util::asset('images/logo.png') }} }}">
    <meta property="fb:page_id" content="">
    <!-- twitter/x -->
    <meta name="twitter:creator" content="{{ Util::config('app_name') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ Util::config('app_name') }}">
    <meta name="twitter:description" content="{{ Util::config('app_name') }}">
    <meta name="twitter:site" content="{{ $home . $version . '/' }}">
    <meta name="twitter:image" content="{{ Util::asset('images/logo.png') }} }}">
    <!-- icons -->
    <link rel="icon" type="image/x-icon" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="128x128" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ Util::asset('images/favicon.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ Util::asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ Util::asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.1.0/styles/default.min.css">
    <link rel="stylesheet" href="{{ Util::asset('css/light.css') }}" id="theme-light">
    <link rel="stylesheet" href="{{ Util::asset('css/dark.css') }}" id="theme-dark" disabled>
    <link rel="stylesheet" href="{{ Util::asset('css/app.css') }}">
</head>

<body>
    @yield('body')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.1.0/highlight.min.js"></script>
    <script src="{{ Util::asset('js/app.js') }}"></script>
</body>

</html>