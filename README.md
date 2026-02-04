# [SISTESIS]

### Descripción
Sistema integral para la gestión y seguimiento de tesis de la Universidad Autónoma de Nayarit, en la unidad academica de economia,
permitiendo la digitalización de procesos institucionales, asi mismo agilizando la gestion de comites estudiantiles a cargo de estudiantes de tesis.

### Tecnologías Utilizadas
Este proyecto fue construido con las siguientes tecnologías:
* **Backend:** PHP 8.x y Laravel 10.
* **Frontend:** JavaScript (ES6+), jQuery, Bootstrap 5.
* **Base de Datos:** MySQL (Diseñada en MySQL Workbench).
* **Gestión de Paquetes:** NPM y Composer.
* **Librerías Clave:** ProseMirror, Chart.js, SweetAlert2, DataTables.

###  Características Principales
* **Gestión de Tesis:** Flujo completo desde el registro de tesis hasta la aprobación.
* **Dashboard Estadístico:** Visualización de datos en tiempo real con Chart.js para el coordinador de la carrera/maestria.
* **Editor Enriquecido:** Integración de ProseMirror para descripciones detalladas.
* **Sistema de Roles:** Autenticación y permisos para Alumnos, Docentes y Administradores.
* **Tablas Dinámicas:** Filtros y búsquedas rápidas con DataTables.
* **sistema de recuperacion de contraseña:** Recuperacion de contraseña mediante email del usuario.
 
###  Capturas de Pantalla
> *Tip: Aquí puedes arrastrar una imagen o screenshot de tu sistema dentro de GitHub para que se genere el link.*
![Inicio de sesion](Screenshots/Login.png)
![Dashboard de SISTESIS para coordinador](Screenshots/Login-Coordinador.png)
![Control de usuarios](Screenshots/User-Control.png)
![Control de comites](Screenshots/Comite-Control.png)
![Control de tesis](Screenshots/tesis-control.png)
![Asignacion de usuarios de comite](Screenshots/Assgnment-Commitees.png)
![Asignacion de roles en comite](Screenshots/Asiggnment-roles.png)
![Vizualisacion de controles de tesis del director de tesis](Screenshots/Tesis%20director's-controls.png)
![Visualizacion de tesis para miembros del comite](Screenshots/Visualization%20committee%20tesis.png)
![Visualizacion de tesis del alumno](Screenshots/Tesis-Visualization.png)
![Editor para escribir avance de tesis](Screenshots/Write-Tesis.png)

###  Instalación y Configuración
Para correr este proyecto localmente, sigue estos pasos:

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/samiDroi/seguimientotesis.git](https://github.com/samiDroi/seguimientotesis.git)
   cd seguimientotesis
   
2. **Instalar Dependencias**
   ```bash
   composer install

3. **Instalar dependencias de Frontend (NPM)**
   ```bash
   npm install 
   npm run build # o npm run dev si es para desarrollo activo

4. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate

5. **Configurar base de datos**
   Abre el archivo `.env` en tu editor de texto y asegúrate de actualizar las siguientes líneas con los datos de tu servidor local de MySQL:
   
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_de_tu_bd
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña

   ```bash
   php artisan migrate --seed