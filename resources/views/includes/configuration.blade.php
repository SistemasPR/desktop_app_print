<!--Modal Configuration-->
<div class="fixed bg-black/50 w-screen h-screen top-0 left-0 flex justify-center items-center transition-opacity -z-50 opacity-0"
    aria-modal="false" role="dialog" id="modal-configuration-print">
    <div class="w-96 h-auto bg-white rounded-lg shadow-lg p-4">
        <h3 class="font-semibold text-lg text-center">Configuración</h3>
        <form action="">
            <div class="w-full flex flex-col my-2">
                <label for="" class="text-xs text-gray-400 mb-1">Impresoras</label>
                <select name="" id="printer_selected"
                    class="border border-red-200 rounded-md px-3 py-2 outline-none focus:border-red-500 focus:border-2">
                    <option value="">1</option>
                </select>
            </div>
            <div class="w-full flex justify-between items-center my-2">
                <label for="all_categories" class="text-xs text-gray-400 mb-1">¿Pertenece a todas las categorias?</label>
                <input type="checkbox" value="all_categories" id="all_categories" class="w-auto" onchange="util.inputCheck(this,'category_selected')">
            </div>
            <div class="w-full flex flex-col my-2">
                <label for="" class="text-xs text-gray-400 mb-1">Categorias</label>
                <select name="" id="category_selected" 
                    class="border border-red-200 rounded-md px-3 py-2 outline-none focus:border-red-500 focus:border-2 disabled:bg-gray-300">
                    <option value="">1</option>
                </select>
            </div>
            <div class="flex flex-wrap">
                <button class="w-4/12 bg-gray-400 border rounded-lg py-2 text-white transition hover:bg-gray-600"
                onclick="util.closeModal('modal-configuration-print')" type="button">Cancelar</button>
                <button class="w-8/12 bg-red-500 border rounded-lg py-2 text-white transition hover:bg-red-600"
                    type="button" onclick="auth.login()">Guardar</button>
            </div>
        </form>
    </div>
</div>
