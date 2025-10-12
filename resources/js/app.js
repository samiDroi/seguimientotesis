
import './bootstrap';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';

import 'datatables.net-responsive-bs5';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.css';
// import 'bootstrap';
// import 'datatables.net-dt/js/dataTables.dataTables';
// import 'datatables.net-responsive/js/dataTables.responsive';  // Importa el archivo correcto
import Swal from 'sweetalert2';
import "./usuarios/registros.js";
import "./usuarios/SearchUsers.js";

import "./Comites/Roles.js";
import "./Comites/EditComite.js";
import "./Comites/Index.js";
import "./Comites/AtachMembers.js"

import "./Academico/Unidades.js";
import "./Academico/Programas.js";

import "./Tesis/ComentarioTesis.js";
import "./Tesis/ShowComentariosTesis.js";
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
import '../css/componentes.css';






