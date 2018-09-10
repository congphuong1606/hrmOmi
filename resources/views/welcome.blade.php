<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="/js/assets/img/logo/icon-logo.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/js/assets/img/logo/icon-logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="icon" href="./js/assets/img/logo/icon-logo.png">
    <title>Ominext Human Resource Management</title>

    <base href="/">
    <!-- Bootstrap core CSS     -->
    <link href="./js/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="manifest" href="./js/manifest.json">


    <script src="./js/assets/js/jquery.min.js"></script>
    <!--  Paper Dashboard core CSS    -->
    <link href="./js/assets/css/paper-dashboard.css?v={{BUILD_VERSION}}" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="./js/assets/css/demo.css?v={{BUILD_VERSION}}" rel="stylesheet"/>
    <link href="./js/assets/css/custom.css?v={{BUILD_VERSION}}" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    {{--<script>--}}
        {{--function myFunction() {--}}
            {{--var contentMinHeight = screen.height - 280;--}}
            {{--var elem = document.getElementById('main-panel-content');--}}
            {{--elem.style.minHeight = contentMinHeight + 'px';--}}
        {{--}--}}
        {{--window.onload = myFunction;--}}
    {{--</script>--}}
    <!--  Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="./js/assets/css/themify-icons.css" rel="stylesheet">
    {{--<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>--}}
    @if(BUILD_PROD)
        <link href="./js/styles.bundle.css?v={{BUILD_VERSION}}" rel="stylesheet">
    @endif
</head>
<body class="perfect-scrollbar-off">
<app-root>
    <div class="loader-container">
        <div class="loader"></div>
    </div>
</app-root>
<script type="text/javascript" src="./js/inline.bundle.js?v={{BUILD_VERSION}}"></script>
<script type="text/javascript" src="./js/polyfills.bundle.js?v={{BUILD_VERSION}}"></script>
<script type="text/javascript" src="./js/scripts.bundle.js?v={{BUILD_VERSION}}"></script>
@if(!BUILD_PROD)
    <script type="text/javascript" src="./js/styles.bundle.js?v={{BUILD_VERSION}}"></script>
    <script type="text/javascript" src="./js/vendor.bundle.js?v={{BUILD_VERSION}}"></script>
@endif
<script type="text/javascript" src="./js/main.bundle.js?v={{BUILD_VERSION}}"></script>
</body>

</html>
