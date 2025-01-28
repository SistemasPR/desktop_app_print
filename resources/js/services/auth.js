const save = {
    loginPrintApp : function (data_send,action) {
        const url = action;
        const data = data_send;

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
            if(data.result.error){
                Toastify({
                    text: `${data.result.message}`,
                    duration: 3000,
                    style: {
                        background: "red",
                        color: "white"
                    },
                    newWindow: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                }).showToast();
            }else{
                window.location = data.result.url;
            }
            
        })
        .catch(error => {
            console.error("Error:", error); // Manejar el error
            closeLoad(); // Asegurarse de cerrar el indicador de carga incluso en caso de error
        });
    }
}

export default save;