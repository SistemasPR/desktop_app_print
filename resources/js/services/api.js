const conexion = {
    apiGetCompanys : function () {
        return new Promise((resolve, reject) => {
            fetch(`https://pos.pizzaraul.work/api/app/store/getCompanys`)
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
    apiGetStores : function (company_id) {
        return new Promise((resolve, reject) => {
            fetch(`https://pos.pizzaraul.work/api/app/store/getStores?company_id=${company_id}`)
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
    }
}

export default conexion;