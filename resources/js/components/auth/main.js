import conexion from "../../services/api";
import save from "../../services/auth";
const auth = {
    getCompanys: async () => {
        let data = await conexion.apiGetCompanys();
        document.querySelector('#company_id').innerHTML = '';
        let html = `
            <option value="">Selecciona compañia</option>
        `;
        document.querySelector('#company_id').innerHTML += html;
        data.forEach(element => {
            let html = `
                <option value="${element.id}">${element.title}</option>
            `;
            document.querySelector('#company_id').innerHTML += html;
        });
    },
    getStores: async () => {
        let company_id = document.getElementById('company_id').value;
        let data = await conexion.apiGetStores(company_id);
        document.querySelector('#store_id').innerHTML = '';
        let html = `
            <option value="">Selecciona tienda</option>
        `;
        document.querySelector('#store_id').innerHTML += html;
        data.forEach(element => {
            let html = `
                <option value="${element.id}">${element.title}</option>
            `;
            document.querySelector('#store_id').innerHTML += html;
        });
    },
    login: () => {
        let password = document.getElementById('store_password').value;
        let store_id = document.getElementById('store_id').value;
        let form = document.getElementById('auth_login_form');
        let url = form.action;
        const formData = new FormData(form);
        let data_send = Object.fromEntries(formData.entries()); // convertir a json los objetos del formulario
        if(store_id == ""){
            Toastify({
                text: `Debe seleccionar una tienda`,
                duration: 3000,
                style: {
                    background: "red",
                    color: "white"
                },
                newWindow: true,
                gravity: "bottom", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
            }).showToast();
            return false;
        }
        if(password == "" || password.length == 0){
            Toastify({
                text: `Ingrese correctamente la contraseña`,
                duration: 3000,
                style: {
                    background: "red",
                    color: "white"
                },
                newWindow: true,
                gravity: "bottom", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
            }).showToast();
            return false;
        }
        save.loginPrintApp(data_send,url);
    }
}
export default auth;