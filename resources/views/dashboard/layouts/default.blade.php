<!DOCTYPE html>
	<html lang="pt-br">
	<head>
		<title>@yield('view_title')</title>
		<meta charset="utf-8">
		<meta name="description" content="@yield('description')">
		<meta name="keywords" content="@yield('keywords')">
		<meta name="author" content="{{ session()->get('author') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Asap:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Catamaran:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">

        <!-- Styles -->
		<link rel="stylesheet" href="{{ asset('css/default.css') }}">
		@yield('css')

		<!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

		<!-- JQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

        <script>
            $(function ()
            {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
		@yield('scripts')
	</head>

	<body class="bg-light">
		@include('dashboard.layouts.partials.header')
		@yield('content')
		@include('dashboard.layouts.partials.footer')

		<!-- Popper -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>

		<!-- Font Awesome -->
		<!-- <script src="https://kit.fontawesome.com/0a215c7e97.js" crossorigin="anonymous"></script> -->
		@yield('footer_scripts')
	</body>
</html>
