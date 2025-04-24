<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body>
    <!-- PRESENTACIÓN DE IMPRESORA -->
    <header class="w-full flex justify-between items-center px-4 bg-red-600 py-2">
        <p class="text-white font-semibold uppercase">Aplicativo de impresora</p>
        <p class="text-white font-semibold text-sm">v.{{env('APP_VERSION')}}</p>
    </header>
    <section class="w-full">
        <div class="w-full flex flex-col items-center justify-center p-6 space-x-6">
            <!--card-->
            <div class="w-96 h-50 shadow-md p-4 rounded-lg">
                {{-- <form action="{{ route('auth.login') }}" id="auth_login_form" method="POST" class="flex-none">
                    @csrf
                    <div class="flex flex-col mb-4">
                        <label for="" class="text-xs text-gray-400 mb-2">Compañia</label>
                        <select id="company_id" name="company_id" class="border border-red-200 rounded-md px-3 py-2 outline-none focus:border-red-500 focus:border-2" onchange="auth.getStores()">
                            <option value="">Selecciona la compañia</option>
                        </select>
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="" class="text-xs text-gray-400 mb-2">Tienda</label>
                        <select id="store_id" name="store_id" class="border border-red-200 rounded-md px-3 py-2 outline-none focus:border-red-500 focus:border-2">
                            <option value="">Selecciona tu tienda</option>
                        </select>
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="" class="text-xs text-gray-400 mb-2">Contraseña</label>
                        <input type="password" name="store_password" class="border border-red-200 rounded-md px-3 py-2 outline-none focus:border-red-500 focus:border-2" placeholder="****" id="store_password">
                    </div>
                    <div class="flex flex-col mb-4">
                        <button class="w-full bg-red-500 border rounded-lg py-2 text-white transition hover:bg-red-600" type="button" onclick="auth.login()">Ingresar</button>
                    </div>
                </form> --}}
                <p class="text-2xl font-bold">Manten abierto este aplicativo al momento de estar en venta o recibiendo pedidos</p>
                <button class="w-full bg-red-500 rounded-md text-white mt-5 border-[0px]" onclick="exitAplicattion()">Cerrar Aplicativo</button>
            </div>
            <span class="text-gray-300 text-xs mt-4">Todos los derechos reservados Pizza Raul ©</span>
        </div>
    </section>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
<script>
    function exitAplicattion() {
        const userConfirmed = confirm("¿Estás seguro de que deseas cerrar la aplicación?");   
        if (!userConfirmed) {
            return false;
            // Aquí puedes agregar la lógica para cerrar la aplicación
        }
        remote.app.exit();
    }
</script>
</html>