<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
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
