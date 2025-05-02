window.addEventListener('beforeunload', (e) => {
    const confirmed = confirm("¿Estás seguro de que deseas cerrar la aplicación?");
    if (!confirmed) {
        e.preventDefault();
        e.returnValue = false;
    }
});
