<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>@yield('view_title')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
	</head>

	<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box; position: relative; margin: 0; height: 100%; width: 100%; background-color: #e7e6dd; line-height: 1.4; color: #333333;">
		@include('responses.layouts.partials.header')
		@yield('view_content')
		@include('responses.layouts.partials.footer')
	</body>

</html>
