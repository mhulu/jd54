<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="staraw">
    <meta name="description" content="世达奥科">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Wemesh') }}登录</title>
    <link href={{mix('css/app.css')}} rel="stylesheet">
    <script>
        window.Wemesh = <?php echo json_encode(['csrfToken' => csrf_token()]);?>
    </script>
</head>
<body>
    <div id="app">
    	<div class="overlay vcenter">
    		<div class="overlay_mask"></div>
    		<div class="overlay_content">
    			<div class="container">
    					<div class="col-md-6 col-sm-12 col-md-offset-3 col-lg-offset-3 padding-top-20  login">
    						<logo class="center"></logo>
    						@yield('content')
    					</div>
    			</div>
    		</div>
    	</div>
    </div>
    <script src={{mix('js/mini.js')}}></script>
</body>
</html>
