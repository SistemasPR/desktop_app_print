import conexion from "./services/api";

const deploy = {
    getStores  : async () => {
        let data = await conexion.apiGetStores();
        let storeSelect = document.getElementById("storeSelect");
        let first_dom =  `<option value="" disabled selected>— Selecciona una tienda —</option>`;
        storeSelect.innerHTML = first_dom;
        data.forEach(element => {
            let dom =
             `
             <option value="${element.id}">${element.company_id} - ${element.title}</option>
             `
             storeSelect.innerHTML += dom;
        });
    },
    saveConfig : async () => {
        let store_id = document.getElementById("storeSelect").value;
        let passwordInput = document.getElementById("passwordInput").value;
        let data = await conexion.apiSaveConfig(passwordInput,store_id);
        if(data.status == "correct"){
            showToast('✓ Configuración guardada correctamente');
            setTimeout(() => {
                window.location = data.url;
            }, 1000);
        }else{
            alert(data.message);
        }
    },
    printKitchenTicket : async (order,items,printer) => {
        let data = await conexion.printKitchenTicket(order,items,printer);
        addLog(data.type,data.message_alert,data.tag_alert);
    },
    printTicket : async (correlativo,items,order,storecontent,printers) => {
        let data = await conexion.printTicket(correlativo,items,order,storecontent,printers);
        addLog(data.type,data.message_alert,data.tag_alert);
    },
    printCloseCash : async (printer,store,apertura_s,suma_S,ventas,transactions_S,usuario,store_balance,mercaderia) => {
        let data = await conexion.printCloseCash(printer,store,apertura_s,suma_S,ventas,transactions_S,usuario,store_balance,mercaderia);
        addLog(data.type,data.message_alert,data.tag_alert);
    },
    printPaloteo : async (datas, store,printer) => {
        let data = await conexion.printPaloteo(datas, store,printer);
        addLog(data.type,data.message_alert,data.tag_alert);
    },
    printInventory : async (datas, store,printer) => {
        let data = await conexion.printInventory(datas, store,printer);
        addLog(data.type,data.message_alert,data.tag_alert);
    },
    printMovement : async (datas, store,printer) => {
        let data = await conexion.printMovement(datas, store,printer);
        addLog(data.type,data.message_alert,data.tag_alert);
    },
    printTesting : async (store,printer) => {
        let data = await conexion.printTesting(store,printer);
        addLog(data.type,data.message_alert,data.tag_alert);
    },
    printApplicationOn : async (store,printer) => {
        let data = await conexion.printApplicationOn();
    },
    jobQueue : async (machine_id,job_id) => {
        let data = await conexion.jobQueue(machine_id,job_id);
        return data;
    }
}

export default deploy;