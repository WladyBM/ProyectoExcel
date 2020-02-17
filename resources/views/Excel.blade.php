<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <title>Creado</title>
    </head>
    <div class="d-flex justify-content-center">
        <div class="col-md-7">
            @yield('contenido')
        </div>
    </div>
</html>
