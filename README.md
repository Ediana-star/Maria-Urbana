# María Urbana

Tienda online desarrollada con **Vue** (frontend) y **Laravel + SQLite** (backend), ejecutada mediante **Docker Compose**.

Este repositorio contiene el proyecto y los archivos necesarios para poner en marcha la versión publicada de María Urbana en una computadora con Windows y Docker Desktop.

## Contenido

- [1. ¿Qué necesito para ejecutar el proyecto?](#1-qué-necesito-para-ejecutar-el-proyecto)
- [2. Descargar el proyecto desde GitHub](#2-descargar-el-proyecto-desde-github)
- [3. Conocer la estructura de carpetas](#3-conocer-la-estructura-de-carpetas)
- [4. Instalar y abrir Docker Desktop](#4-instalar-y-abrir-docker-desktop)
- [5. Preparar el proyecto la primera vez](#5-preparar-el-proyecto-la-primera-vez)
- [6. Abrir la tienda](#6-abrir-la-tienda)
- [7. Acceder desde otra computadora de la misma red](#7-acceder-desde-otra-computadora-de-la-misma-red)
- [8. Comandos para el uso diario](#8-comandos-para-el-uso-diario)
- [9. Actualizar el proyecto después de modificar el código](#9-actualizar-el-proyecto-después-de-modificar-el-código)
- [10. Conservar los productos, pedidos e imágenes](#10-conservar-los-productos-pedidos-e-imágenes)
- [11. Problemas frecuentes](#11-problemas-frecuentes)

## 1. ¿Qué necesito para ejecutar el proyecto?

- Una computadora con **Windows 10 u 11 de 64 bits**, compatible con la versión actual de Docker Desktop.
- **Docker Desktop** instalado y funcionando.
- Conexión a Internet para descargar el proyecto y construir las imágenes de Docker la primera vez.
- Los archivos completos de este repositorio.

Docker Desktop con el motor WSL 2 requiere que la computadora cumpla sus requisitos de hardware y Windows. Entre ellos se encuentran la virtualización habilitada y, para WSL 2, al menos 8 GB de RAM. Consultá los requisitos actualizados en la [documentación oficial de Docker para Windows](https://docs.docker.com/desktop/setup/install/windows-install/).

No es necesario instalar PHP, Composer, Node.js, npm ni SQLite por separado: Docker utiliza las versiones y herramientas incluidas en las imágenes del proyecto.

## 2. Descargar el proyecto desde GitHub

Hay dos maneras de obtener el proyecto.

### Opción A: descargarlo como ZIP (no requiere instalar Git)

1. Abrí la página de este repositorio en GitHub.
2. Presioná el botón verde **Code**.
3. Elegí **Download ZIP**.
4. Cuando termine la descarga, buscá el archivo ZIP en la carpeta Descargas.
5. Hacé clic derecho sobre el ZIP y elegí **Extraer todo**.
6. Elegí una ubicación fácil de encontrar, por ejemplo el Escritorio.
7. Abrí la carpeta extraída. Dentro debe estar la carpeta principal `Maria-Urbana` (el nombre puede incluir un sufijo agregado por GitHub).

### Opción B: clonar el repositorio con Git

Esta opción sirve para quienes ya tienen Git instalado y desean descargar el proyecto mediante comandos.

1. En GitHub, abrí este repositorio y presioná **Code**.
2. En la pestaña **HTTPS**, copiá la dirección que aparece.
3. Abrí PowerShell en la carpeta donde querés guardar el proyecto.
4. Escribí el siguiente comando, reemplazando el texto de ejemplo por la dirección que copiaste:

```powershell
git clone URL-HTTPS-DEL-REPOSITORIO
```

5. Presioná Enter. Git creará una carpeta con el proyecto descargado.

> **Importante:** para continuar, hay que abrir PowerShell dentro de la carpeta principal del proyecto, es decir, la carpeta que contiene `compose.yaml`.

## 3. Conocer la estructura de carpetas

La carpeta principal debe conservar esta estructura. No muevas ni renombres las carpetas principales ni el archivo `compose.yaml`:

```text
Maria-Urbana/
├── backend-maria-urbana/
│   ├── .env.example
│   ├── database/
│   │   └── database.sqlite
│   └── storage/
│       └── app/
│           └── public/
├── tienda-ropa-prototipo1/
└── compose.yaml
```

También hay otros archivos necesarios dentro de las carpetas, como los `Dockerfile`. Conservá todos los archivos y carpetas que vienen en el repositorio.

- `backend-maria-urbana`: contiene el servidor y la API desarrollados con Laravel.
- `tienda-ropa-prototipo1`: contiene la interfaz de la tienda desarrollada con Vue.
- `compose.yaml`: indica a Docker cómo construir y ejecutar los dos servicios.
- `database/database.sqlite`: almacena los datos de la tienda, como productos y pedidos.
- `storage/app/public/`: contiene las imágenes que utiliza la tienda.

## 4. Instalar y abrir Docker Desktop

Si Docker Desktop ya está instalado y funciona, podés pasar directamente a la sección 5.

1. Entrá en la página oficial: [Descargar Docker Desktop para Windows](https://docs.docker.com/desktop/setup/install/windows-install/).
2. Descargá el instalador para tu computadora.
3. Abrí el archivo descargado y seguí las instrucciones del instalador.
4. Cuando el instalador ofrezca usar **WSL 2**, elegí esa opción si está disponible. Es la configuración recomendada para la mayoría de las instalaciones de Windows.
5. Si el instalador solicita reiniciar Windows, reiniciá la computadora.
6. Después del reinicio, abrí **Docker Desktop** desde el menú Inicio.
7. Esperá a que Docker Desktop termine de iniciar y muestre que el motor está funcionando.

Si Windows indica que WSL no está instalado o necesita actualizarse:

1. Abrí PowerShell **como administrador**. Para hacerlo, buscá PowerShell en el menú Inicio, hacé clic derecho y elegí **Ejecutar como administrador**.
2. Ejecutá:

```powershell
wsl --install
```

3. Si se solicita, reiniciá la computadora.
4. Luego abrí nuevamente PowerShell como administrador y ejecutá:

```powershell
wsl --update
```

5. Abrí Docker Desktop otra vez. Para más información, consultá la [guía oficial de Docker sobre WSL 2](https://docs.docker.com/desktop/features/wsl/).

## 5. Preparar el proyecto la primera vez

Estos pasos se realizan **una sola vez por computadora**, antes de abrir la tienda por primera vez.

### Paso 1. Abrir PowerShell en la carpeta del proyecto

1. Abrí el Explorador de archivos de Windows.
2. Entrá en la carpeta principal `Maria-Urbana`, donde se encuentra `compose.yaml`.
3. Hacé clic en la barra de dirección de la ventana del Explorador.
4. Escribí `powershell` y presioná Enter.

Se abrirá PowerShell directamente en esa carpeta. Para comprobar que estás en el lugar correcto, ejecutá:

```powershell
Get-ChildItem
```

En la lista debe aparecer `compose.yaml`, además de las dos carpetas principales.

### Paso 2. Crear la configuración privada de Laravel

Laravel necesita un archivo llamado `.env` para guardar la configuración local de la aplicación. Ese archivo no se descarga desde GitHub: cada computadora debe crear el suyo a partir de la plantilla `.env.example`.

Copiá y pegá este comando en PowerShell:

```powershell
if (-not (Test-Path .\backend-maria-urbana\.env)) {
    Copy-Item .\backend-maria-urbana\.env.example .\backend-maria-urbana\.env
}
```

Presioná Enter. El comando crea `.env` solamente si todavía no existe. Si ya tenías un `.env`, lo conserva y no lo reemplaza.

### Paso 3. Comprobar que estén los datos de la tienda

En la versión publicada del proyecto deben estar estos elementos:

- `backend-maria-urbana/database/database.sqlite`
- La carpeta `backend-maria-urbana/storage/app/public/`

No los borres, no los reemplaces por archivos vacíos y no cambies sus nombres. La base de datos contiene los datos de la tienda y la carpeta `public` contiene las imágenes.

### Paso 4. Construir las imágenes de Docker

Una imagen es el paquete que Docker prepara con los programas y archivos necesarios para ejecutar cada parte del proyecto. Este paso se realiza la primera vez y también cuando se modifican archivos que requieren reconstruir la aplicación.

Ejecutá:

```powershell
docker compose build
```

Esperá a que termine. La primera construcción necesita Internet porque descarga componentes y dependencias. Si termina sin mostrar un error, continuá con el siguiente paso.

### Paso 5. Generar la clave de Laravel

Laravel necesita una clave propia para funciones internas de seguridad. Cada instalación debe generar la suya.

Ejecutá:

```powershell
docker compose run --rm backend php artisan key:generate --show
```

PowerShell mostrará una clave que comienza con `base64:`.

1. Seleccioná y copiá la clave completa que apareció en pantalla.
2. Abrí el archivo `backend-maria-urbana/.env` con el Bloc de notas o un editor de texto.
3. Buscá la línea que dice `APP_KEY=`.
4. Pegá la clave inmediatamente después del signo igual. Por ejemplo:

```text
APP_KEY=base64:CLAVE_GENERADA_EN_ESTA_COMPUTADORA
```

5. Guardá el archivo y cerrá el editor.

Este paso se realiza una sola vez para esa instalación. No generes una clave nueva cada vez que abras la tienda.

### Paso 6. Iniciar la tienda

En PowerShell, ejecutá:

```powershell
docker compose up -d
```

Docker iniciará el frontend y el backend en segundo plano. La primera vez puede tardar mientras prepara los servicios.

Para comprobar que se iniciaron correctamente, ejecutá:

```powershell
docker compose ps
```

Deben aparecer los servicios `frontend` y `backend` con estado `Up`.

> **Sobre la base de datos:** la versión publicada incluye `database.sqlite`, por lo que no es necesario crear otra base ni ejecutar migraciones para esta instalación. No ejecutes `migrate` sobre la base incluida sin una razón concreta. Si estás preparando deliberadamente una instalación nueva con una base vacía, primero hay que crear las tablas mediante `docker compose exec backend php artisan migrate --force`; esto no recupera los productos ni los pedidos de otra base.

## 6. Abrir la tienda

Cuando los contenedores estén funcionando, abrí un navegador (por ejemplo, Edge o Chrome) y escribí esta dirección:

**http://localhost:8080**

Esa es la dirección principal de María Urbana en la computadora donde se está ejecutando Docker.

El proyecto utiliza estos puertos:

| Puerto | Uso |
|---|---|
| `8080` | Tienda web (frontend). Es la dirección principal para entrar. |
| `8000` | Backend y API. La tienda lo utiliza internamente para comunicarse con Laravel. |

Para entrar a la tienda, usá el puerto **8080**.

## 7. Acceder desde otra computadora de la misma red

También es posible abrir la tienda desde otra computadora conectada a la misma red Wi-Fi o red local.

### En la computadora donde se ejecuta Docker

1. Abrí PowerShell.
2. Ejecutá:

```powershell
ipconfig
```

3. Buscá el apartado del adaptador Wi-Fi que está conectado a la red.
4. Anotá la dirección que aparece junto a **Dirección IPv4**. Por ejemplo: `192.168.1.25`.

### En la otra computadora

1. Conectala a la misma red Wi-Fi.
2. Abrí un navegador.
3. Escribí `http://` seguido de la dirección IPv4 y `:8080`. Por ejemplo:

```text
http://192.168.1.25:8080
```

Usá la dirección IPv4 real del equipo donde corre Docker, no el ejemplo de esta guía. La IP puede cambiar al conectarse a otra red o al reiniciar el router.

**Importante:** la computadora donde corre Docker debe permanecer encendida, conectada a la red y con Docker Desktop funcionando. Si Windows muestra una ventana del Firewall preguntando si permite el acceso, autorizalo únicamente en redes privadas o de confianza.

Este acceso es para una red local. El proyecto no queda publicado en Internet y esta configuración utiliza HTTP, no HTTPS.

## 8. Comandos para el uso diario

Todos estos comandos se ejecutan en PowerShell, desde la carpeta principal que contiene `compose.yaml`.

### Ver si la tienda está funcionando

```powershell
docker compose ps
```

Si los servicios aparecen como `Up`, están en funcionamiento.

### Detener temporalmente la tienda (opción recomendada)

```powershell
docker compose stop
```

Detiene los servicios, pero conserva los contenedores. No borra los archivos del proyecto, la base de datos ni las imágenes.

### Volver a iniciar la tienda

```powershell
docker compose start
```

Vuelve a iniciar los contenedores que se habían detenido con `stop`. Después, abrí `http://localhost:8080` en el navegador.

### Reiniciar los servicios

```powershell
docker compose restart
```

Detiene y vuelve a iniciar los servicios. No elimina los datos.

### Ver los registros de actividad

```powershell
docker compose logs -f
```

Muestra mensajes de los servicios para ayudar a encontrar errores. Para salir de esta vista, presioná `Ctrl + C`. Eso cierra la visualización de los registros, pero no detiene la tienda.

### Eliminar los contenedores y la red del proyecto

```powershell
docker compose down
```

Este comando detiene y elimina los contenedores y la red creados por Compose. En la configuración de este proyecto, la base SQLite y las imágenes están guardadas en carpetas del proyecto, por lo que no se eliminan al ejecutar `down`.

Para volver a crear e iniciar los contenedores después de usar `down`, ejecutá:

```powershell
docker compose up -d
```

> **Recomendación:** para cerrar la tienda al terminar de usarla, utilizá `docker compose stop`. Para abrirla de nuevo, utilizá `docker compose start`. No necesitás usar `down` para el apagado habitual.

## 9. Actualizar el proyecto después de modificar el código

Si modificaste archivos del frontend, del backend o los Dockerfile, puede ser necesario reconstruir las imágenes para que Docker utilice los cambios.

Desde PowerShell, en la carpeta principal del proyecto, ejecutá:

```powershell
docker compose up -d --build
```

Este comando construye nuevamente las imágenes si corresponde y deja los servicios iniciados. Los archivos de datos que están en las carpetas del proyecto se mantienen.

Si otra persona publicó cambios en GitHub y querés descargarlos usando Git, abrí PowerShell en la carpeta del proyecto y ejecutá:

```powershell
git pull
```

Después, si esos cambios afectan el código que se construye en Docker, ejecutá:

```powershell
docker compose up -d --build
```

Si descargaste el proyecto como ZIP, no tenés el historial ni el comando `git pull`: descargá una nueva copia desde GitHub y seguí las indicaciones correspondientes para actualizar tus archivos, conservando tus datos locales.

## 10. Conservar los productos, pedidos e imágenes

Los datos importantes de esta versión se encuentran en estas ubicaciones:

- **Productos y pedidos:** `backend-maria-urbana/database/database.sqlite`.
- **Imágenes:** `backend-maria-urbana/storage/app/public/`.

No borres, muevas ni reemplaces esos elementos si querés conservar los datos de la tienda.

El archivo `backend-maria-urbana/.env` contiene configuración propia de cada instalación y no debe publicarse ni compartirse. El repositorio incluye `.env.example`, que funciona como plantilla para crear el archivo local.

No utilices `docker compose down -v` como forma habitual de apagar el proyecto. Para detenerlo normalmente, usá `docker compose stop`.

## 11. Problemas frecuentes

### Docker Desktop no está abierto

Abrí Docker Desktop desde el menú Inicio y esperá a que indique que el motor está funcionando. Luego volvé a PowerShell y repetí el comando.

### PowerShell dice que no reconoce `docker` o `docker compose`

Comprobá que Docker Desktop esté instalado y abierto. Luego ejecutá:

```powershell
docker --version
docker compose version
```

Si todavía no reconoce los comandos, reiniciá Docker Desktop. Si el problema continúa, revisá la instalación de Docker Desktop y WSL 2.

### La tienda no abre en `localhost:8080`

1. Comprobá que Docker Desktop esté funcionando.
2. En PowerShell, dentro de la carpeta del proyecto, ejecutá:

```powershell
docker compose ps
```

3. Si los servicios no aparecen como `Up`, ejecutá:

```powershell
docker compose up -d
```

4. Si el problema continúa, revisá los registros:

```powershell
docker compose logs -f
```

### Otra computadora no puede entrar

Comprobá que ambos equipos estén conectados a la misma red, que la computadora principal permanezca encendida y que estés utilizando su dirección IPv4 actual con el puerto `8080`. Revisá también que el Firewall de Windows permita el acceso en la red privada.

---

**María Urbana** — Proyecto de tienda online con Vue, Laravel, SQLite y Docker Compose.
