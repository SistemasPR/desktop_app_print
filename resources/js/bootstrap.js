import axios from 'axios';
import { io } from "socket.io-client";

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let ip_address =  'https://pos.app.pizzaraul.com';

window.Socket = io(ip_address, {
    path: '/skt/socket.io', // Agrega la ruta aquí
    transports: ['websocket'], 
});
