<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <title>Document</title>
    <base href="/">
</head>
<body>

    @if(Auth::check())
    <script>
        window.Laravel = {!!json_encode([
            'user' => Auth::user(),
            'isLogged' => true
        ])!!}
    </script>
    
    @else
     <script>
        window.Laravel = {!!json_encode([
            'isLogged' => false
        ])!!}
    </script>
    @endif
    
    <div id="app">
        
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</body>
</html>
