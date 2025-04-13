<!DOCTYPE html>
<html>
<head>
	<title>education</title>
	<meta name="viewport" content="width=device-width">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/css/all.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/css/all.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/css/lightbox.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/css/flexslider.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/css/owl.carousel.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/css/owl.theme.default.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/css/jquery.rateyo.css') }}"/>
	<!-- <link rel="stylesheet" type="text/css" href="css/jquery.mmenu.all.css" /> -->
	<!-- <link rel="stylesheet" type="text/css" href="css/meanmenu.min.css"> -->
	<link rel="stylesheet" type="text/css" href="{{ asset('template/inner-page-style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('template/style.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700" rel="stylesheet">
</head>
<body>
	<div id="page" class="site" itemscope itemtype="http://schema.org/LocalBusiness">
		<header class="site-header">
			<div class="top-header">
				<div class="container">
					<div class="top-header-left">
						<div class="top-header-block">
							<a href="mailto:info@educationpro.com" itemprop="email"><i class="fas fa-envelope"></i> info@educationpro.com</a>
						</div>
						<div class="top-header-block">
							<a href="tel:+9779813639131" itemprop="telephone"><i class="fas fa-phone"></i> +977 9813639131</a>
						</div>
					</div>
					<div class="top-header-right">
						<div class="social-block">
							<ul class="social-list">
								<li><a href=""><i class="fab fa-viber"></i></a></li>
								<li><a href=""><i class="fab fa-google-plus-g"></i></a></li>
								<li><a href=""><i class="fab fa-facebook-square"></i></a></li>
								<li><a href=""><i class="fab fa-facebook-messenger"></i></a></li>
								<li><a href=""><i class="fab fa-twitter"></i></a></li>
								<li><a href=""><i class="fab fa-skype"></i></a></li>
							</ul>
						</div>
						@if(Route::has('login'))
						@auth
						<div class="login-block">
							<a href="#">Salut X</a>
						</div>
						@else
						<div class="login-block">
							<a href="{{route('login')}}">Se connecter</a>&nbsp;&nbsp;
							<a href="{{route('register')}}">S'enregister</a>
						</div>
						@endauth
						@endif

					</div>
				</div>
			</div>
			<!-- Top header Close -->
			<div class="main-header">
				<div class="container">
					<div class="logo-wrap" itemprop="logo">
						<img src="{{ asset('template/images/site-logo.jpg') }}" alt="Logo Image">
						<!-- <h1>Education</h1> -->
					</div>
					<div class="nav-wrap">
						<nav class="nav-desktop">
							<ul class="menu-list">
								<li><a href="{{route('uHome')}}">Acceuil</a></li>
								<li><a href="{{route('listing')}}">Nos foramations</a></li>
								<li><a href="about.html">Informations pratiques</a></li>
							</ul>
						</nav>
						<div id="bar">
							<i class="fas fa-bars"></i>
						</div>
						<div id="close">
							<i class="fas fa-times"></i>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- Header Close -->