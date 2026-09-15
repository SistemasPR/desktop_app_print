# Diagnóstico del build NativePHP para Windows

Esta guía cubre el build **Windows x64** de este proyecto con NativePHP Desktop y `electron-builder` 26.8.1. Se ejecuta desde la raíz del proyecto. Los argumentos son palabras: `win x64`; los valores numéricos que aparecen en código interno de Electron no son argumentos de Artisan.

La raíz del repositorio ignora `/nativephp` en `.gitignore`. Por ello, los cambios locales de `nativephp/electron/package.json` (como `predev` y `prebuild`) y otros archivos publicados de Electron **no viajan automáticamente a otra PC mediante Git**. En esa máquina compruebe `package.json` antes de asumir que esos pasos se ejecutan solos; si faltan, ejecute los comandos de reconstrucción de esta guía o publique y adapte el proyecto Electron allí.

## Capturar una traza útil

En PowerShell:

```powershell
$started = Get-Date
$log = Join-Path $PWD ('native-build-win-x64-' + $started.ToString('yyyyMMdd-HHmmss') + '.log')
php artisan native:build win x64 -v --no-interaction 2>&1 | Tee-Object -FilePath $log
$artisanExit = $LASTEXITCODE
$installer = Get-ChildItem -LiteralPath 'nativephp\electron\dist' -Filter '*-setup.exe' -File -ErrorAction SilentlyContinue |
    Where-Object { $_.LastWriteTime -ge $started } |
    Sort-Object LastWriteTime -Descending |
    Select-Object -First 1
[pscustomobject]@{ ArtisanExit = $artisanExit; NewInstaller = $installer.FullName; Log = $log }
```

Use el **primer error real** del log, no solo las últimas líneas. Busque `Failed to download`, `Permission denied`, `Could not resolve`, `Cannot create symbolic link`, `cannot execute` y `errorOut`. El build pasa por varias capas: Artisan prepara la app, Composer poda dependencias de producción, NPM compila recursos, Electron Vite genera `out/main/index.js`, `electron-builder` empaqueta y NSIS crea el instalador. El error de una capa puede aparecer después como un archivo faltante en la siguiente.

**No confíe solo en `ArtisanExit`:** en la versión instalada, `BuildCommand::buildOrPublish()` ejecuta `npm run build:win-x64` sin llamar a `throw()` ni revisar `failed()`. Vimos un error de `electron-builder` con código de salida de Artisan 0. Confirme que el instalador sea nuevo y que el log alcance `building block map` sin errores posteriores. Mantenga el log privado: los procesos de build pueden imprimir datos de configuración y URLs de descarga temporales.

## Errores que ya reprodujimos

| Síntoma en el log | Capa y causa comprobada | Comprobación / acción |
| --- | --- | --- |
| `NATIVEPHP_PHP_BINARY_PATH` sospechosa, línea `join(...)` de `php.js` | En nuestra ejecución la variable sí apuntó a `vendor/nativephp/php-bin/bin/win/x64/php-8.3.zip`; PHP se copió correctamente. | Compruebe `Binary Source`, `PHP version` y `Copied PHP binary to`. Si falta la ruta o el ZIP, revise la instalación de `nativephp/php-bin` y el valor pasado por `LocatesPhpBinary`. |
| `No electron app entry file found: ...out/main/index.js` | `electron-plugin/dist/server/pdfPageSize.js` faltaba; Electron Vite no produjo el archivo de salida. En modo `--watch`, el mensaje de éxito posterior fue engañoso. | Ejecute `npm.cmd run plugin:build` desde `nativephp/electron`; después, con las variables que NativePHP pasa, el build debe mostrar `out/main/index.js`. El `predev` y `prebuild` locales ya reconstruyen el plugin. |
| `Vite manifest not found at public/build/manifest.json` | Faltaban los recursos web de Laravel. | Ejecute `npm.cmd run build` en la raíz y compruebe `public/build/manifest.json`. El `predev` y `prebuild` locales ya lanzan este build. |
| Composer: `Permission denied` al descargar `nativephp/php-bin` o escribir en `vendor/.../build/app` | El entorno de ejecución restringía la caché de Composer y la descarga; ocurrió en nuestro primer intento dentro del sandbox. | Repita el build en una terminal que tenga acceso de escritura a la copia de build y a la caché de Composer. No lo confunda con el fallo posterior de 7-Zip. |
| 7-Zip: `Cannot create symbolic link ...darwin/...libcrypto.dylib` y `libssl.dylib` | `electron-builder` extrajo `winCodeSign-2.6.0.7z`. El archivo incluye dos enlaces de macOS; esta cuenta de Windows no tenía el privilegio de crearlos. | Prepare la caché local con las herramientas Windows siguiendo el procedimiento siguiente, o habilite el privilegio de enlaces simbólicos en esa máquina. |

## Preparar winCodeSign en otra PC

Este paso afecta **solo la máquina que compila**. En una PC nueva, primero ejecute el build para que `electron-builder` descargue `winCodeSign-2.6.0.7z` y confirme que el error es el mismo. Luego, desde PowerShell en la raíz del proyecto:

```powershell
$cache = Join-Path $env:LOCALAPPDATA 'electron-builder\Cache\winCodeSign'
$archive = Get-ChildItem -LiteralPath $cache -Filter '*.7z' -File |
    Sort-Object LastWriteTime -Descending |
    Select-Object -First 1
$target = Join-Path $cache 'winCodeSign-2.6.0'
$sevenZip = Join-Path $PWD 'nativephp\electron\node_modules\7zip-bin\win\x64\7za.exe'
& $sevenZip x -y $archive.FullName ('-o' + $target)
Test-Path (Join-Path $target 'rcedit-x64.exe')
Test-Path (Join-Path $target 'windows-10\x64\signtool.exe')
& (Join-Path $PWD 'nativephp\electron\node_modules\app-builder-bin\win\x64\app-builder.exe') download-artifact --name winCodeSign
```

La extracción con 7-Zip puede terminar con **dos errores de enlace simbólico**; son los archivos `darwin/10.12/lib/libcrypto.dylib` y `libssl.dylib`. Solo continúe si ambos `Test-Path` devuelven `True` y `app-builder` informa `found existing` para `winCodeSign-2.6.0`. Si aparece cualquier otro error o falta una herramienta Windows, no considere válida la caché. En nuestra máquina, esta extracción parcial permitió aplicar los recursos a `printpr.exe` y generar el instalador NSIS.

Después repita la captura del build. Si la PC usa otra versión de `electron-builder` o descarga otro paquete, revise primero su log y la estructura de caché; este procedimiento está comprobado para `winCodeSign-2.6.0` con la versión de este proyecto.

## Resultado de referencia y límite de distribución

En la prueba del 15-09-2026, el build llegó a `building block map` y generó `nativephp/electron/dist/PrintPR-4.0.4-setup.exe` y su `.blockmap`. NativePHP mostró `INSECURE BUILD`: no había un bundle seguro, por lo que el instalador incluye archivos fuente. Resolver la extracción de `winCodeSign` no cambia esa condición.

Referencias: [incidencia de winCodeSign en electron-builder](https://github.com/electron-userland/electron-builder/issues/8149), [privilegio de enlaces simbólicos de Windows](https://learn.microsoft.com/en-us/previous-versions/windows/it-pro/windows-10/security/threat-protection/security-policy-settings/create-symbolic-links).
