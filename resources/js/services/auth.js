const save = {
    loginPrintApp : function (store_id,password) {
        const url = "https://pos.pizzaraul.com/api/app/login/loginPrintApp"
        const data = {
            store_id,
            password
        };

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data) // Convertir los datos a formato JSON
        })
        .then(response => {
            if (!response.ok) {
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
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Convertir la respuesta a JSON
        })
        .then(data => {
            // Manejar la respuesta exitosa
            console.log(data);
            
            window.location = '/';
        })
        .catch(error => {
            console.error("Error:", error); // Manejar el error
            closeLoad(); // Asegurarse de cerrar el indicador de carga incluso en caso de error
        });
    }
}

export default save;