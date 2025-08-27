@echo off
REM Configurar rutas y datos
set "MYSQL=D:\xampp\mysql\bin\mysql.exe"
set "ARCHIVO_RESTAURAR=D:\RESPALDOS_MYSQL\base_kam_<nombre_del_respaldo>.sql"
set "BASE_DE_DATOS=base_kam"
set "USUARIO=root"

REM Verificar si el archivo de respaldo existe
if exist "%MYSQL%" (
    if exist "%ARCHIVO_RESTAURAR%" (
        "%MYSQL%" -h localhost -u %USUARIO% %BASE_DE_DATOS% < "%ARCHIVO_RESTAURAR%"
        echo Base de datos "%BASE_DE_DATOS%" restaurada exitosamente desde "%ARCHIVO_RESTAURAR%".
    ) else (
        echo Error: No se encontró el archivo de respaldo "%ARCHIVO_RESTAURAR%".
    )
) else (
    echo Error: No se encontró mysql en la ruta "%MYSQL%".
)

REM Terminar el script
exit
