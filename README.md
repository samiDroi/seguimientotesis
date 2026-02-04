> **English Version**: [Click here to read the English version of this README](README.en.md)



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
 
###  Capturas del sistema
*Figura 1: Inicio de sesion del sistema*![Inicio de sesion](Screenshots/Login.png)

---

*Figura 2: Dashboard de SISTESIS para coordinador*![Dashboard de SISTESIS para coordinador](Screenshots/Login-Coordinador.png)


---


*Figura 3: Control de usuarios*![Control de usuarios](Screenshots/User-Control.png)


---


*Figura 4: Control de comites*![Control de comites](Screenshots/Comite-Control.png)


---


*Figura 5: Control de tesis*![Control de tesis](Screenshots/tesis-control.png)


---


*Figura 6: Asignacion de usuarios de comite*![Asignacion de usuarios de comite](Screenshots/Assgnment-Commitees.png)

---

*Figura 7: Asignacion de roles en comite*![Asignacion de roles en comite](Screenshots/Asiggnment-roles.png)

---

*Figura 8: Vizualisacion de controles de tesis del director de tesis*![Vizualisacion de controles de tesis del director de tesis](Screenshots/Tesis%20director's-controls.png)

---

*Figura 9: Visualizacion de tesis para miembros del comite*![Visualizacion de tesis para miembros del comite](Screenshots/Visualization%20committee%20tesis.png)

---

*Figura 10: Visualizacion de tesis del alumno*![Visualizacion de tesis del alumno](Screenshots/Tesis-Visualization.png)

---

*Figura 9: Editor para escribir avance de tesis*![Editor para escribir avance de tesis](Screenshots/Write-Tesis.png)

---

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

6. **Ejecutar las migraciones**
   ```bash
   php artisan migrate --seed