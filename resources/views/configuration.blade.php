<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Configuración de tienda</title>
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
        label {
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
            border: 1px solid rgba(99, 102, 241, 0.25);
            box-shadow: 0 0 60px rgba(99, 102, 241, 0.08), 0 8px 32px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
        }

        .glow-dot {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.18) 0%, transparent 70%);
            position: fixed;
            top: -80px;
            right: -80px;
            pointer-events: none;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e5e7eb;
            transition: all 0.2s;
        }

        .input-field:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.6);
            background: rgba(99, 102, 241, 0.06);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .input-field option {
            background: #111118;
            color: #e5e7eb;
        }

        .btn-save {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }

        .btn-save::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #818cf8, #a78bfa);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .btn-save:hover::after {
            opacity: 1;
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        .btn-save span {
            position: relative;
            z-index: 1;
        }

        .badge {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
        }

        .eye-btn {
            color: #6b7280;
            transition: color 0.2s;
        }

        .eye-btn:hover {
            color: #a5b4fc;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp 0.5s ease both;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        .delay-4 {
            animation-delay: 0.42s;
        }

        .toast {
            transition: all 0.35s cubic-bezier(.4, 0, .2, 1);
            transform: translateY(20px);
            opacity: 0;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
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

    <div class="glow-dot"></div>

    <!-- CARD -->
    <div class="card rounded-2xl w-full max-w-md p-8 animate-fade-up">

        <!-- Header -->
        <div class="mb-8 animate-fade-up delay-1">
            <div class="flex items-center gap-3 mb-3">
                <div
                    class="w-8 h-8 rounded-lg bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span
                    class="badge text-xs font-semibold px-2.5 py-1 rounded-full tracking-wide uppercase">Soporte</span>
            </div>

            <h1 class="text-2xl font-bold text-white tracking-tight leading-tight">
                Configuración<br /><span class="text-indigo-400">de tienda</span>
            </h1>
            <p class="text-gray-400 text-sm mt-2 leading-relaxed">
                Elige correctamente la tienda a configurar.
            </p>
        </div>

        <!-- Divider -->
        <div class="border-t border-white/5 mb-7 animate-fade-up delay-1"></div>

        <!-- Form -->
        <form id="configForm" class="space-y-6">

            <!-- Selector tienda -->
            <div class="animate-fade-up delay-2">
                <label class="block text-xs font-semibold text-gray-300 uppercase tracking-widest mb-2">
                    Tienda
                </label>
                <div class="relative">
                    <select id="storeSelect"
                        class="input-field w-full rounded-xl px-4 py-3 pr-10 text-sm appearance-none cursor-pointer">
                        <option value="" disabled selected>— Selecciona una tienda —</option>
                    </select>
                    <!-- Chevron -->
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Contraseña -->
            <div class="animate-fade-up delay-3">
                <label class="block text-xs font-semibold text-gray-300 uppercase tracking-widest mb-2">
                    Contraseña
                </label>
                <div class="relative">
                    <input id="passwordInput" type="password" placeholder="••••••••••••"
                        class="input-field w-full rounded-xl px-4 py-3 pr-12 text-sm" autocomplete="current-password" />
                    <!-- Toggle visibility -->
                    <button type="button" id="togglePassword" onclick="togglePass()"
                        class="eye-btn absolute inset-y-0 right-3 flex items-center px-1 focus:outline-none"
                        aria-label="Mostrar contraseña">
                        <!-- Eye open (default) -->
                        <svg id="iconEye" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye closed (hidden) -->
                        <svg id="iconEyeOff" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.978 9.978 0 012.223-3.592M6.228 6.228A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.978 9.978 0 01-2.68 4.126M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 text-xs mt-1.5 ml-1">Mínimo 8 caracteres recomendados.</p>
            </div>

            <!-- Botón guardar -->
            <div class="animate-fade-up delay-4 pt-1">
                <button type="button"
                    class="btn-save w-full rounded-xl py-3.5 text-white font-semibold text-sm tracking-wide"
                    onclick="deploy.saveConfig()">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar configuración
                    </span>
                </button>
            </div>

        </form>
    </div>

    <!-- Toast -->
    <div id="toast"
        class="toast fixed bottom-6 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 z-50">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <span id="toastMsg">Configuración guardada</span>
    </div>






    <!-- Banner de actualización estilo Notion/Figma -->
    <div id="update-banner" style="display:none;">
        <div class="update-content">
            <span>🚀</span>
            <div>
                <strong id="update-title">Nueva versión disponible</strong>
                <span id="update-subtitle">Descargando actualización...</span>
            </div>
            <div class="update-progress-bar" id="progress-wrap">
                <div class="update-progress-fill" id="progress-fill"></div>
            </div>
            <div class="update-actions" id="update-actions" style="display:none;">
                <button onclick="installUpdate()">Actualizar ahora</button>
                <button onclick="dismissUpdate()">Al próximo inicio</button>
            </div>
        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', () => {
            deploy.getStores();
        })

        function togglePass() {
            const input = document.getElementById('passwordInput');
            const eye = document.getElementById('iconEye');
            const eyeOff = document.getElementById('iconEyeOff');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }

        function handleSave(e) {
            e.preventDefault();
            const store = document.getElementById('storeSelect').value;
            const pass = document.getElementById('passwordInput').value;

            if (!store) {
                showToast('⚠️ Selecciona una tienda', true);
                return;
            }
            if (!pass) {
                showToast('⚠️ Ingresa la contraseña', true);
                return;
            }

        }

        function showToast(msg, error = false) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toastMsg');
            toastMsg.textContent = msg;
            toast.style.background = error ? '#dc2626' : '#4f46e5';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2800);
        }

        // Hay una nueva versión → mostrar banner con progreso
        Native.on('update-available', (data) => {
            const banner = document.getElementById('update-banner');
            document.getElementById('update-title').textContent =
                `Nueva versión ${data.version} disponible`;
            banner.style.display = 'flex';
        });

        // Progreso de descarga
        Native.on('update-progress', (data) => {
            document.getElementById('progress-fill').style.width = data.percent + '%';
            document.getElementById('update-subtitle').textContent =
                `Descargando... ${data.percent}%`;
        });

        // Descarga completa → mostrar botones
        Native.on('update-downloaded', () => {
            document.getElementById('update-subtitle').textContent =
                '¡Lista para instalar!';
            document.getElementById('progress-wrap').style.display = 'none';
            document.getElementById('update-actions').style.display = 'flex';
        });

        function installUpdate() {
            fetch('/native/update-install');
        }

        function dismissUpdate() {
            document.getElementById('update-banner').style.display = 'none';
        }
    </script>
</body>

</html>
