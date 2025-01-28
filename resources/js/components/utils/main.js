import conexion from "../../services/api";

const util = {
    openModal : (id_modal) => {
        let modal = document.getElementById(id_modal);
        modal.classList.remove('-z-50')
        modal.classList.remove('opacity-0')
        modal.classList.add('z-50')
    },
    closeModal : (id_modal) => {
        let modal = document.getElementById(id_modal);
        modal.classList.remove('z-50')
        modal.classList.add('-z-50')
        modal.classList.add('opacity-0')
    },
    printerSelected : async () => {
        let store_id = document.getElementById('store_id').value;
        let data = await conexion.apiGetPrinters(store_id);
        
        document.querySelector('#printer_selected').innerHTML = '';
        
        let html = `
            <option value="">Selecciona impresora</option>
        `;

        document.querySelector('#printer_selected').innerHTML += html;

        data.printers.forEach(element => {
            let domEl = 
            `
                <option value="${element.id}">${element.title}</option>
            `;
            document.querySelector('#printer_selected').innerHTML += domEl;
        });
    },
    categorySelected : async () => {
        let company_id = document.getElementById('company_id').value;
        let data = await conexion.apiGetCategories(company_id);

        document.querySelector('#category_selected').innerHTML = '';
        
        let html = `
            <option value="">Selecciona la categoria</option>
        `;

        document.querySelector('#category_selected').innerHTML += html;

        data.forEach(element => {
            let domEl = 
            `
                <option value="${element.id}">${element.title}</option>
            `;
            document.querySelector('#category_selected').innerHTML += domEl;
        });
    },
    inputCheck : (e,input) => {
        let inp = document.getElementById(input);
        if(e.checked){
            inp.setAttribute("disabled", "disabled");
        }else{
            inp.removeAttribute("disabled");
        }
    }
    
}
export default util;