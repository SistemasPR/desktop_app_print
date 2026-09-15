const conexion = {
    apiGetCompanys : function () {
        return new Promise((resolve, reject) => {
            fetch(`https://pos.pizzaraul.com/api/app/store/getCompanys`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => resolve(data.result))
                .catch(error =>{
                    Toastify({
                        text: `Contactarse con soporte de manera inmediata`,
                        duration: 3000,
                        style: {
                            background: "red",
                            color: "white"
                        },
                        newWindow: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                    }).showToast();
                    reject(error)
                } );
        });
    },
    apiGetStores : function () {
        return new Promise((resolve, reject) => {
            fetch(`https://pos.pizzaraul.com/api/app/store/getStores`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => resolve(data.result))
                .catch(error =>{
                    Toastify({
                        text: `Contactarse con soporte de manera inmediata`,
                        duration: 3000,
                        style: {
                            background: "red",
                            color: "white"
                        },
                        newWindow: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                    }).showToast();
                    reject(error)
                } );
        });
    },
    apiGetPrinters: (store_id) => {
        return new Promise((resolve, reject) => {
            fetch(`https://pos.pizzaraul.work/api/print/getPrintersByStoreId?store_id=${store_id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => resolve(data.result))
                .catch(error =>{
                    Toastify({
                        text: `Contactarse con soporte de manera inmediata`,
                        duration: 3000,
                        style: {
                            background: "red",
                            color: "white"
                        },
                        newWindow: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                    }).showToast();
                    reject(error)
                } );
        });
    },
    apiGetCategories: (company_id) => {
        return new Promise((resolve, reject) => {
            fetch(`https://pos.pizzaraul.work/api/app/categories?company_id=${company_id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => resolve(data.result))
                .catch(error =>{
                    Toastify({
                        text: `Contactarse con soporte de manera inmediata`,
                        duration: 3000,
                        style: {
                            background: "red",
                            color: "white"
                        },
                        newWindow: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                    }).showToast();
                    reject(error)
                } );
        });
    },
    apiSaveConfig : async  (password,store_id) => {
        let url = `/api/save-configuration`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    password: password,
                    store_id: store_id
                })
            });

            const data = await res.json();
            return data;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printKitchenTicket : async  (order,items,printer) => {
        let url = `/api/ticketComandaApiV2`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    order: order,
                    items: items,
                    printer: printer
                })
            });

            const data = await res.json();
            return data;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printTicket : async  (correlativo,items,order,storecontent,printers) => {
        let url = `/api/ticketBoletadeVentaApiV2`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    order: order,
                    items: items,
                    storecontent: storecontent,
                    printers: printers,
                    correlativo: correlativo
                })
            });

            const data = await res.json();
            return data;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printCloseCash : async  (printer,store,apertura_s,suma_S,ventas,transactions_S,usuario,store_balance,mercaderia) => {
        let url = `/api/ticketCierreApiV2`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    printer: printer,
                    store: store,
                    apertura_s: apertura_s,
                    suma_S: suma_S,
                    ventas: ventas,
                    transactions_S: transactions_S,
                    usuario: usuario,
                    store_balance: store_balance,
                    mercaderia: mercaderia
                })
            });

            const data = await res.json();
            return data;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printPaloteo : async  (data, store,printer) => {
        let url = `/api/ticketPaloteoApiV2`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    data: data,
                    store: store,
                    printer: printer
                })
            });

            const resu = await res.json();
            return resu;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printInventory : async  (data, store,printer) => {
        let url = `/api/ticketInventarioApiV2`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    data: data,
                    store: store,
                    printer: printer
                })
            });

            const resu = await res.json();
            return resu;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printMovement : async  (movimiento, store,printer) => {
        let url = `/api/ticketMovimientoApiV2`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    movimiento: movimiento,
                    store: store,
                    printer: printer
                })
            });

            const resu = await res.json();
            return resu;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printTesting : async  (store,printer) => {
        let url = `/api/ticketTestingApiV2`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    store: store,
                    printer: printer
                })
            });

            const resu = await res.json();
            return resu;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    printApplicationOn : async  (store,printer) => {
        let url = `/api/getApplicationOn`;
        try {
            const res = await fetch(`${url}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            });

            const resu = await res.json();
            return resu;
        } catch (err) {
            return { status: "error", message: err };
        }
    },
    jobQueue : async (machine_id,job_id) => {
        let url = `https://pos.app.pizzaraul.com/skt/websocket/event/printer/take-job`;
        try {
            const res = await fetch(`${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    machine_id: machine_id,
                    job_id: job_id
                })
            });

            const resu = await res.json();
            return resu;
        } catch (err) {
            return { status: "error", message: err };
        }
    }
}

export default conexion;