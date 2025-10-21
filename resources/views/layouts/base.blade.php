
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <title>Landing page</title>
    @yield("css")
    {{-- @vite(['resources/css/app.scss', 'resources/js/app.js','resources/css/root.css','resources/css/variables.css']) --}}
    @vite(['resources/js/app.js', 'resources/css/app.scss','resources/css/variables.css','resources/css/root.css'])

    <!-- Tus estilos personalizados si tienes -->
    <style>
        :root {
            --color-belize: #2980b9;
            --color-delete: #e74c3c;
        }
    #sidebar {
    background-color: white;
    box-shadow: 4px 0 6px -1px rgba(0,0,0,0.1);
    transition: width 0.3s ease-in-out;
    overflow: hidden;
    width: 20%; /* ancho expandido */
}

#sidebar.collapsed {
    width: 60px; /* ancho plegado solo iconos */
}

#sidebar .option {
    display: flex;
    align-items: center; /* centra icono y texto verticalmente */
    justify-content: flex-start;
    gap: 10px;
    padding: 10px 15px;
    white-space: nowrap; /* evita que el texto se rompa */
}

#sidebar .link-text {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

#sidebar.collapsed .link-text {
    opacity: 0;
    transform: translateX(-10px); /* un pequeño desplazamiento para animación */
    pointer-events: none; /* no se puede clicar el texto cuando está oculto */
}



#sidebar .option i {
    min-width: 24px; /* asegura que todos los iconos tengan el mismo ancho */
    text-align: center;
}
#sidebar .option, .collapse-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    transition: 0.3s ease;
}
.collapse-btn .btn-text {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Cuando el sidebar se pliega */
#sidebar.collapsed .collapse-btn .btn-text {
    opacity: 0;
    transform: translateX(-10px);
    pointer-events: none; /* no clickeable cuando está oculto */
}

/* Opcional: animar el icono para girar cuando se pliega */
#sidebar.collapsed .collapse-btn i {
    transition: transform 0.3s ease-in-out;
    transform: rotate(180deg); /* o cualquier animación */
}
.principal{
    width: 100%
}

.option{
    height: 3rem;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
    
   
}

.option:hover{
       
    background-color: var(--color-morado-4);
    height: 5rem;
    cursor: pointer;
    transition: ease-in-out 5s;
   
}

.option:hover a, .option:hover i{
    color: white;
}

    </style>
    <style>
/* 🔹 Layout 70/30 */
.layout-wrapper {
  display: flex;
  height: calc(100vh - 70px); /* Resta el alto del navbar */
  overflow: hidden;
}

.sidebar-area {
  flex: 0 0 20%;
  max-width: 30%;
  background-color: white;
  box-shadow: 4px 0 6px -1px rgba(0,0,0,0.1);
  overflow-y: auto;
}

.content-area {
  flex: 0 0 70%;
  max-width: 70%;
  overflow-y: auto;
  padding: 1.5rem;
}

/* 🔹 Ajuste en pantallas pequeñas */
@media (max-width: 768px) {
  .layout-wrapper {
    flex-direction: column;
  }
  .sidebar-area {
    flex: 0 0 auto;
    max-width: 100%;
  }
  .content-area {
    flex: 1;
    max-width: 100%;
  }
}
</style>

  <!-- TinyMCE CDN -->
<!-- Place the first <script> tag in your HTML's <head> -->
</head>
<body>

    <!-- 🟦 Navbar superior -->
    <nav class="navbar" style="height: 70px; background-color:var(--color-azul-obscuro);">
        <div class="container-fluid">
            <div class="row d-flex justify-content-between align-items-center w-100">
                <!-- Título -->
                <div class="col-8 d-flex align-items-center pb-3">
                    <!-- Botón hamburguesa visible solo en pantallas pequeñas -->
                    <button class="btn btn-light d-md-none me-3 " type="button" data-bs-toggle="offcanvas" data-bs-target="#sideMenu" aria-controls="sideMenu">
                        ☰
                    </button>
                    <p class="fs-1 display-4 text-light mb-0">Sistesis</p>
                </div>

                <!-- Info usuario -->
                <div class="col-4 d-flex justify-content-end align-items-center gap-2 flex-nowrap overflow-hidden">
    <p class="text-light mb-0 text-nowrap small" style="max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
        {{ Auth::user()->correo_electronico }}
    </p>
    <form action="{{ Route('logout') }}" method="POST">
        @csrf
        <button class="btn text-light px-3 py-2" style="background-color: var(--color-delete); white-space: nowrap;" type="submit">
            Cerrar Sesión <i class="fa-solid fa-right-from-bracket ms-1"></i>
        </button>
    </form>
</div>
            </div>
        </div>
    </nav>

     {{-- <div class="container-fluid p-0">
        <div class="row g-0">

            <!-- 🟥 Sidebar -->
            <div class="col-md-3 col-lg-3 col-xl-3 p-0">
                <nav id="sidebar" class="nav flex-column gap-4 pt-5 pb-5 fs-4 text-center vh-100">
                    <div class="sidebar-toggle mb-4">
                        <button id="toggleSidebar" class="btn btn-light">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>

                    <div class="option my-1 py-3 mb-5 mt-2">
                        <a href="{{ Route('home') }}" class="text-decoration-none text-dark fw-semibold diva">
                            <i class="fa-solid fa-house"></i>
                            <span class="link-text">Inicio</span>
                        </a>
                    </div>

                    <div class="option my-1 py-3 mb-5">
                        <a href="{{ Route('info.comites') }}" class="text-decoration-none text-dark fw-semibold diva">
                            <i class="fa-solid fa-user-group"></i>
                            <span class="link-text">Mi comité</span>
                        </a>
                    </div>

                    @if (isDirector() > 0)
                    <div class="option my-1 py-3 mb-5">
                        <a href="{{ Route('tesis.index') }}" class="text-decoration-none text-dark fw-semibold diva">
                            <i class="fa-solid fa-file-word"></i>
                            <span class="link-text">Gestionar tesis</span>
                        </a>
                    </div>
                    @endif

                    <div class="option my-1 py-3 mb-5">
                        <a href="{{ Route('info.unidad') }}" class="text-decoration-none text-dark fw-semibold diva">
                            <i class="fa-solid fa-school"></i>
                            <span class="link-text">Mi unidad</span>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- 🟩 Contenido principal -->
            <div class="col-md-9 col-lg-9 col-xl-9 mt-4 principal px-4">
                @yield('content')
            </div>
        </div>
    </div> --}}
    <div class="layout-wrapper">
    <!-- 🟥 Sidebar -->
    
        <div id="sidebar" class="nav flex-column gap-4 pt-5 pb-5 fs-4 text-center">
            <div class="sidebar-toggle mb-4">
                <button id="toggleSidebar" class="btn btn-light ml-3">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div class="option my-1 py-3 mb-5 mt-2">
                <a href="{{ Route('home') }}" class="text-decoration-none text-dark fw-semibold diva">
                   <i class="fa-solid fa-house"></i> 
                    <span class="link-text"> Inicios</span>
                </a>
            </div>

            <div class="option my-1 py-3 mb-5">
                <a href="{{ Route('info.comites') }}" class="text-decoration-none text-dark  fw-semibold diva">
                    <i class="fa-solid fa-user-group"></i>
                    <span class="link-text"> Mi comité</span>
                </a>
            </div>

            @if (isDirector() > 0)
            <div class="option my-1 py-3 mb-5">
                <a href="{{ Route('tesis.index') }}" class="text-decoration-none text-dark fw-semibold diva">
                    <i class="fa-solid fa-file-word"></i>
                    <span class="link-text"> Gestionar tesis</span>
                </a>
            </div>
            @endif

            <div class="option my-1 py-3 mb-5">
                <a href="{{ Route('info.unidad') }}" class="text-decoration-none text-dark fw-semibold diva">
                    <i class="fa-solid fa-school"></i>
                    <span class="link-text"> Mi unidad</span>
                </a>
            </div>
        </div>
    

    <!-- 🟩 Contenido principal -->
    <div class="content-area">
        @yield('content')
    </div>
</div>

    @include('sweetalert::alert')
    <script src="{{ asset("vendor/sweetalert/sweetalert.all.js") }}"></script>
    <script src="https://cdn.tiny.cloud/1/urwrxmsleu3b744kjom91xeido5jy6oujj95v82jp8ixig9s/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://kit.fontawesome.com/eaefdedbbf.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
    });
});
document.querySelector('#toggleSidebar').addEventListener('click', function() {
    this.classList.toggle('collapse-btn');
});   
    </script>
    @yield('js')
    </body>
</html>