<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bienvenido a tu Tienda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        h1,
        h2,
        label,
        .font-syne {
            font-family: 'Syne', sans-serif;
        }

        .bg-grid {
            background-color: #0a0a0f;
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.07) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .card {
            background: rgba(15, 15, 25, 0.85);
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: 0 0 60px rgba(99, 102, 241, 0.07), 0 8px 32px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
        }

        .glow-dot-tr {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            position: fixed;
            top: -100px;
            right: -100px;
            pointer-events: none;
        }

        .glow-dot-bl {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
            position: fixed;
            bottom: -80px;
            left: -80px;
            pointer-events: none;
        }

        /* LOG PANEL */
        .log-panel {
            background: rgba(5, 5, 12, 0.9);
            border: 1px solid rgba(99, 102, 241, 0.15);
            box-shadow: inset 0 2px 20px rgba(0, 0, 0, 0.4);
        }

        /* Scrollbar del log */
        .log-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .log-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .log-scroll::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.3);
            border-radius: 4px;
        }

        /* ALERTAS */
        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            border-left: 3px solid #10b981;
            border-top: 1px solid rgba(16, 185, 129, 0.15);
            border-right: 1px solid rgba(16, 185, 129, 0.08);
            border-bottom: 1px solid rgba(16, 185, 129, 0.08);
            color: #6ee7b7;
        }

        .alert-success .dot {
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
        }

        .alert-success .tag {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.08);
            border-left: 3px solid #ef4444;
            border-top: 1px solid rgba(239, 68, 68, 0.15);
            border-right: 1px solid rgba(239, 68, 68, 0.08);
            border-bottom: 1px solid rgba(239, 68, 68, 0.08);
            color: #fca5a5;
        }

        .alert-error .dot {
            background: #ef4444;
            box-shadow: 0 0 8px #ef4444;
        }

        .alert-error .tag {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Dot pulsante */
        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.7);
            }
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            animation: pulse-dot 2s ease infinite;
            flex-shrink: 0;
        }

        /* Status pill activo */
        .status-live {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.3
            }
        }

        .blink {
            animation: blink 1.5s ease infinite;
        }

        /* Animaciones entrada */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .afu {
            animation: fadeUp 0.5s ease both;
        }

        .d1 {
            animation-delay: 0.08s;
        }

        .d2 {
            animation-delay: 0.18s;
        }

        .d3 {
            animation-delay: 0.3s;
        }

        .d4 {
            animation-delay: 0.42s;
        }

        /* Entrada de cada log */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .log-entry {
            animation: slideIn 0.3s ease both;
        }

        /* Aviso banner */
        .banner-warn {
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.25);
            color: #fcd34d;
        }

        /* Boton limpiar */
        .btn-clear {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #6b7280;
            transition: all 0.2s;
        }

        .btn-clear:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .counter-badge {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            font-family: 'Syne', sans-serif;
        }

        #update-banner {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #18181b;
            color: #fff;
            padding: 14px 18px;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);
            z-index: 99999;
            min-width: 320px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: sans-serif;
            font-size: 14px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .update-content {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
        }

        .update-content strong {
            display: block;
            margin-bottom: 2px;
        }

        .update-content span#update-subtitle {
            color: #aaa;
            font-size: 12px;
        }

        .update-progress-bar {
            width: 100%;
            height: 4px;
            background: #333;
            border-radius: 4px;
            margin-top: 6px;
            overflow: hidden;
        }

        .update-progress-fill {
            height: 100%;
            background: #6366f1;
            border-radius: 4px;
            transition: width 0.3s ease;
            width: 0%;
        }

        .update-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .update-actions button {
            padding: 6px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }

        .update-actions button:first-child {
            background: #6366f1;
            color: white;
        }

        .update-actions button:last-child {
            background: transparent;
            color: #aaa;
            border: 1px solid #444;
        }
    </style>
</head>

<body class="bg-grid min-h-screen flex items-center justify-center px-4 py-12">

    <div class="glow-dot-tr"></div>
    <div class="glow-dot-bl"></div>
    <input type="hidden" name="" id="store_id_storage" value="{{ $store_id }}">
    <div class="card rounded-2xl w-full max-w-xl p-8">

        <!-- HEADER -->
        <div class="afu d1 mb-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <!-- Icono tienda -->
                        <div
                            class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10" />
                            </svg>
                        </div>
                        <!-- Status live -->
                        <span
                            class="status-live text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 blink inline-block"></span>
                            EN VENTA
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold text-white tracking-tight leading-snug">
                        BIENVENIDO A<br />
                        <span class="text-indigo-400">TU TIENDA</span>
                        <span
                            class="counter-badge text-white text-sm font-bold px-2.5 py-0.5 rounded-lg ml-2 align-middle">1</span>
                    </h1>
                </div>

                <!-- Hora -->
                <div class="text-right shrink-0">
                    <div id="clock" class="font-syne text-indigo-300 text-lg font-semibold tabular-nums"></div>
                    <div id="date" class="text-gray-500 text-xs mt-0.5"></div>
                </div>
            </div>

            <!-- Tipo de impresora -->
            <div class="flex flex-col items-start gap-1 mt-2">
                <p class="text-white text-md font-medium">Elige el tipo de maquina para la impresion </p>
                <select name="" id="type_printer" class="bg-indigo-500 px-2 py-1 font-medium text-white rounded">
                    <option value="3">General</option>
                    <option value="2">Salon</option>
                    <option value="1">Delivery</option>
                </select>
            </div>

            <!-- Aviso -->
            <div class="banner-warn rounded-xl px-4 py-3 mt-4 flex items-center gap-3">
                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
                <ul class="ms-4 list-decimal text-amber-200 text-sm font-medium">
                    <li>No cierres el aplicativo mientras está en venta.</li>
                    <li>Si solo cuentas con una computadora o con islas de atención dejarlo de manera general.</li>
                </ul>
            </div>
        </div>

        <!-- DIVIDER -->
        <div class="border-t border-white/5 mb-6 afu d2"></div>

        <!-- LOG PANEL -->
        <div class="afu d3">
            <!-- Header panel -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17v-2m3 2v-4m3 4v-6M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                    <span class="font-syne text-sm font-semibold text-gray-300 uppercase tracking-widest">Notificaciones
                        / Logs</span>
                    <span id="logCount"
                        class="bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                </div>
                <button onclick="clearLogs()"
                    class="hidden btn-clear text-xs font-medium px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3" />
                    </svg>
                    Limpiar
                </button>
            </div>

            <!-- Panel scroll -->
            <div id="logPanel" class="log-panel log-scroll rounded-xl p-4 h-72 overflow-y-auto flex flex-col gap-2.5">
                <!-- Placeholder vacío -->
                <div id="emptyState" class="flex flex-col items-center justify-center h-full gap-3 text-center">
                    <div
                        class="w-12 h-12 rounded-2xl bg-white/3 border border-white/5 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Sin actividad aún</p>
                        <p class="text-gray-700 text-xs mt-0.5">Los logs de la API aparecerán aquí</p>
                    </div>
                </div>
            </div>
            <p class="text-left text-gray-500 text-xs font-medium">v4.0.4</p>
        </div>

        <audio id="alert_order" loop autoplay >
            <source src="{{ asset('/sounds/fx.mp3') }}" type="audio/mpeg">
        </audio>
        <!-- CONTROLES DEMO -->
        {{-- <div class="afu d4 mt-5 flex gap-3">
      <button onclick="addLog('success')"
        class="flex-1 rounded-xl py-2.5 text-sm font-semibold text-emerald-300 border border-emerald-500/30 bg-emerald-500/8 hover:bg-emerald-500/15 transition-all flex items-center justify-center gap-2">
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
        + Alerta OK
      </button>
      <button onclick="addLog('error')"
        class="flex-1 rounded-xl py-2.5 text-sm font-semibold text-red-300 border border-red-500/30 bg-red-500/8 hover:bg-red-500/15 transition-all flex items-center justify-center gap-2">
        <span class="w-2 h-2 rounded-full bg-red-400"></span>
        + Alerta Error
      </button>
    </div> --}}

    </div>

    <script>
        // ── Reloj ──────────────────────────────────────────
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent =
                now.toLocaleTimeString('es', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            document.getElementById('date').textContent =
                now.toLocaleDateString('es', {
                    weekday: 'short',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── Logs ───────────────────────────────────────────
        const successMessages = [{
                tag: 'API 200',
                msg: 'Transacción aprobada — Pago procesado correctamente.'
            },
            {
                tag: 'STOCK OK',
                msg: 'Inventario sincronizado con el servidor central.'
            },
            {
                tag: 'AUTH OK',
                msg: 'Sesión verificada — Token renovado exitosamente.'
            },
            {
                tag: 'VENTA OK',
                msg: 'Venta #4821 registrada — S/ 148.50 confirmado.'
            },
            {
                tag: 'SYNC OK',
                msg: 'Datos enviados al servidor — Sin errores detectados.'
            },
        ];

        const errorMessages = [{
                tag: 'API 500',
                msg: 'Error interno del servidor — Reintentando en 5s...'
            },
            {
                tag: 'TIMEOUT',
                msg: 'Tiempo de espera excedido al consultar endpoint /ventas.'
            },
            {
                tag: 'AUTH ERR',
                msg: 'Token expirado — No se pudo renovar la sesión.'
            },
            {
                tag: 'STOCK ERR',
                msg: 'Fallo al actualizar inventario — Conexión rechazada.'
            },
            {
                tag: 'DB ERR',
                msg: 'No se pudo escribir en la base de datos local.'
            },
        ];

        let logCount = 0;

        function addLog(type, message_alert, tag_alert) {
            const panel = document.getElementById('logPanel');
            const empty = document.getElementById('emptyState');

            if (empty) empty.remove();

            const pool = type === 'success' ? successMessages : errorMessages;
            const item = pool[Math.floor(Math.random() * pool.length)];

            const now = new Date();
            const time = now.toLocaleTimeString('es', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            const div = document.createElement('div');
            div.className =
                `log-entry rounded-lg px-4 py-3 flex items-start gap-3 alert-${type === 'success' ? 'success' : 'error'}`;
            div.innerHTML = `
        <span class="dot mt-1.5"></span>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-0.5 flex-wrap">
            <span class="tag text-xs font-bold px-2 py-0.5 rounded-md font-syne tracking-wide">${tag_alert}</span>
            <span class="text-xs opacity-50">${time}</span>
          </div>
          <p class="text-sm leading-snug opacity-90">${message_alert}</p>
        </div>
      `;

            panel.appendChild(div);
            panel.scrollTop = panel.scrollHeight;

            logCount++;
            document.getElementById('logCount').textContent = logCount;
        }

        function clearLogs() {
            const panel = document.getElementById('logPanel');
            panel.innerHTML = `
        <div id="emptyState" class="flex flex-col items-center justify-center h-full gap-3 text-center">
          <div class="w-12 h-12 rounded-2xl bg-white/3 border border-white/5 flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
            </svg>
          </div>
          <div>
            <p class="text-gray-500 text-sm font-medium">Sin actividad aún</p>
            <p class="text-gray-700 text-xs mt-0.5">Los logs de la API aparecerán aquí</p>
          </div>
        </div>
      `;
            logCount = 0;
            document.getElementById('logCount').textContent = 0;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let store_id_storage = document.getElementById("store_id_storage").value;
            const audiocontet = document.getElementById("alert_order");
            audiocontet.volume = 0;

            localStorage.setItem("store_id", store_id_storage);

            if (window.Socket) {
                Socket.on("connect", () => {
                    let store_connect = localStorage.getItem("store_id");
                    Socket.emit('joinStoreRoom', store_connect);
                });

                Socket.on('disconnect', (reason) => {
                    console.warn('⚠️ Desconectado del servidor. Motivo:', reason);
                });

                Socket.on('reconnect_attempt', (n) => {
                    console.log(`🔁 Intentando reconectar (#${n})`);
                });

                Socket.on('reconnect', (n) => {
                    console.log(`🔄 Reconectado después de ${n} intentos`);
                    Socket.emit('joinStoreRoom', storeIdSocket); // ← 🔁 ¡Muy importante!
                });

                Socket.on('reconnect_failed', () => {
                    console.error('⛔ No se pudo reconectar después de los intentos');
                });

                Socket.on('printer', async (data) => {
                    if (data.content.store_id == parseInt(localStorage.getItem("store_id"))) {
                        if(data.content.type == "jobqueue"){
                            let type = document.getElementById("type_printer").value;
                            let artype = [1,2,3];

                            switch (parseInt(type)) {
                                case 1:
                                    artype = [1,2];
                                    break;
                            
                                case 2:
                                    artype = [3];
                                    break;
                            
                                default:
                                    break;
                            }
                            if(data.content.action == "app_on"){
                                const result = await deploy.jobQueue("pc-01",data.content.job_id);
                                if (!result.success) return;
                                deploy.printApplicationOn();
                            }else{
                                
                                if(!artype.includes(parseInt(data.content.order.order_type)) ){
                                    return false;
                                }

                                const result = await deploy.jobQueue("pc-01",data.content.job_id);
                                if (!result.success) return;
                                switch (data.content.action) {
                                    case "kitchenticket": //probado
                                        deploy.printKitchenTicket(data.content.order, data.content.items, data
                                            .content.printers);
                                        break;
                                    case "testing": //probado
                                        deploy.printTesting(data.content.store, data.content.printers);
                                        break;
                                    default:
                                        break;
                                }
                            }

                        }else{
                            let type = document.getElementById("type_printer").value;
                            let artype = [1,2,3];
                            console.log(type)
                            switch (parseInt(type)) {
                                case 1:
                                    artype = [1,2];
                                    break;
                            
                                case 2:
                                    artype = [3];
                                    break;
                            
                                default:
                                    break;
                            }
                            console.log(artype)
                            if(data.content.type_printer == "principal"){
                                switch (data.content.action) {
                                    case "ticket":
                                        if(!artype.includes(parseInt(data.content.order.order_type)) ){
                                        }else{
                                            deploy.printTicket(data.content.correlativo, data.content.items, data
                                                .content.order,
                                                data.content.storecontent, data.content.printers);
                                        }
                                        break;
                                    case "movement": //probado
                                        if(parseInt(type) == 1){
                                            break;
                                        }
                                        deploy.printMovement(data.content.data, data.content.store, data.content
                                            .printer);
                                        break;
                                    case "inventory": //probado
                                        if(parseInt(type) == 1){
                                            break;
                                        }
                                        deploy.printInventory(data.content.data, data.content.store, data.content
                                            .printer);
                                        break;
                                    case "paloteo": //probado
                                        if(parseInt(type) == 1){
                                            break;
                                        }
                                        deploy.printPaloteo(data.content.data, data.content.store, data.content
                                            .printer);
                                        break;
                                    case "close_cash": //probado
                                        if(parseInt(type) == 1){
                                            break;
                                        }
                                        deploy.printCloseCash(data.content.printer, data.content.store,
                                            data.content.apertura_s, data.content.suma_S, data.content
                                            .ventas, data.content.transactions_S, data.content.usuario, data
                                            .content.store_balance, data.content.mercaderia);
                                        break;
                                    default:
                                        break;
                                }
                            }else{
                                deploy.printTicket(data.content.correlativo, data.content.items, data
                                            .content.order,
                                            data.content.storecontent, data.content.printers);
                            }
                        }
                    }
                })

                Socket.on('evento',(data) => {

                    if(data.content.action == "creacion" || data.content.action == "created_arrow_delivery"){
                        //orders.insertRowFirstElement(data.content.order)

                        // let miAudio = document.getElementById("alert_order");
                        // if (miAudio.paused) { // Verificar si el audio está pausado
                        //     miAudio.play().then(() => {
                        //     //console.log('✅ Sonido reproducido');
                        //     }).catch(err => {
                        //         addLog("error",'🚫 No se pudo reproducir el sonido:' + err,"NOTIFICACION");
                        //     });
                        // }
                        audiocontet.volume = 1;
                        // Notification.requestPermission().then(permission => {
                        //     if (permission === 'granted') {
                        //         const notification = new Notification('¡Nueva Orden!', {
                        //             body: 'Delivery esperando por ser atendido.',
                        //         });

                        //         // Reproducir un sonido con la notificación
                        //         const audio = new Audio('/sounds/fx.mp3');
                        //         if (audio.paused) { // Verificar si el audio está pausado
                        //         audio.play().then(() => {
                        //             //console.log('✅ Sonido reproducido');
                        //         }).catch(err => {
                        //             console.error('🚫 No se pudo reproducir el sonido:', err);
                        //         });
                        //         }
                        //     }
                        // });

                    }
                })
                
                Socket.on('sound-stop',(data) => {
                    let miAudio = document.getElementById("alert_order");
                    audiocontet.volume = 0;
                })

            } else {
                console.warn("SOCKET NO ESTAN ACTIVADAS");

            }

        });
        async function job(job_id) {
            let result = await deploy.jobQueue("pc-01", job_id);
            return result;
        }
    </script>

</body>

</html>
