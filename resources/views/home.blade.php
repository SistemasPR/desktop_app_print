@extends('layouts.app')
@section('content')
    <section class="bg-white p-4 w-full">
        <div class="flex justify-between item-center">
            <h2 class="text-lg"><u>Impresoras configuradas en el punto de venta</u></h2>
            <button
                class="w-40 h-auto bg-red-400 rounded-lg p-2 text-white transition-colors hover:bg-red-600 hover:shadow-md "
                onclick="util.openModal('modal-configuration-print')">Configuración</button>
        </div>
        <div class="w-full flex flex-wrap">
            <div class="w-4/12 h-28 px-2 py-2 my-2">
                <div class="w-full h-full shadow-md flex flex-col p-4">
                    <div class="w-full flex items-center justify-between">
                        <p class="font-bold">Impresora 1</p>
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Categorias:</p>
                </div>
            </div>
        </div>
    </section>
    @include('includes.configuration')
@endsection
@section('scripts')
    <script>
       window.addEventListener("load", () => {
            util.printerSelected();
            util.categorySelected();
        });
    </script>
@endsection