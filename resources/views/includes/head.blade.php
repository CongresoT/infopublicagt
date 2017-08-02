<head>
	<link rel="icon" type="image/png" href="http://www.infopublicagt.org/img/infopublicagt-favicon.png">
	<meta property="og:url" content="http://www.infopublicagt.org" />
	<meta property="og:image" content="http://www.infopublicagt.org/img/infopublicagt.png" />
	<meta property="og:title" content="InfoPublicaGT" />
	<meta property="og:description" content="Verifica cuánto cumplen los Sujetos Obligados con la información pública de oficio de la Ley de Accseo información Pública"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'InfoPublicaGT') }}</title>
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="{{ config('app.description','') }}">
    <meta name="keywords" content="{{ config('app.keywords','') }}" />
    <meta name="author" content="{{ config('app.author','') }}">
    

    <!-- Custom CSS & Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">


    <!-- Custom Fonts -->
    <link rel="stylesheet" href="{{ "/css/font-awesome/css/font-awesome.min.css" }}">
    <link href="//fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    @yield('otherIncludes')
    
	@if ( config('app.debug', '') == false )
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-100950561-1', 'auto');
		  ga('send', 'pageview');

		</script>
	@endif
	
</head>