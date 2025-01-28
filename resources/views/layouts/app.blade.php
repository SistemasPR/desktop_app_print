<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Impresoras</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="w-screen h-screen relative overflow-hidden">
    <input type="hidden" id="store_id" value="{{$store->store_id}}">
    <input type="hidden" id="company_id" value="{{$store->company_id}}">
    <header class="w-full flex justify-between items-center px-4 bg-red-600 py-2">
        <p class="text-white font-semibold uppercase">Impresoras: Tienda {{$store->store_name}}</p>
        <p class="text-white font-semibold text-sm">v.{{env('APP_VERSION')}}</p>
    </header>
    @yield('content')
    @yield('scripts')
</body>
</html>