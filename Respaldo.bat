@echo off
REM Activar modo silencioso
echo Iniciando respaldo automáticamente...

REM Obtener la fecha y hora actuales
for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "dia_hora=%dt:~0,4%-%dt:~4,2%-%dt:~6,2%_%dt:~8,2%-%dt:~10,2%-%dt:~12,2%"

REM Configurar rutas y datos
set "MYSQLDUMP=C:\xampp\mysql\bin\mysqldump.exe"
set "CARPETA_RESPALDO=C:\Users\usuario\Downloads\RESPALDOS_MYSQL"
set "BASE_DE_DATOS=base_kam"
set "USUARIO=root"
set "PASSWORD="

REM Crear la carpeta de respaldo si no existe
if not exist "%CARPETA_RESPALDO%" (
    mkdir "%CARPETA_RESPALDO%"
)

REM Verificar si mysqldump existe
if not exist "%MYSQLDUMP%" (
    exit /b
)

REM Realizar el respaldo
"%MYSQLDUMP%" -h localhost -u %USUARIO% -p%PASSWORD% %BASE_DE_DATOS% > "%CARPETA_RESPALDO%\%BASE_DE_DATOS%_%dia_hora%.sql"

REM Verificar si el respaldo se creó correctamente
if not exist "%CARPETA_RESPALDO%\%BASE_DE_DATOS%_%dia_hora%.sql" (
    echo Error: Respaldo no creado. >> "%CARPETA_RESPALDO%\log_error.txt"
)

REM Terminar sin interacción
exit                      
