<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\UnidadAcademica;
use App\Http\Controllers\AcademicControl\UnidadController;
use App\Http\Controllers\AcademicControl\ProgramaController;
use App\Http\Controllers\AcademicDocs\PlanDeTrabajoController;
use App\Http\Controllers\Admin\ComiteController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Auth\ResetPwsdController;
use App\Http\Controllers\Admin\ShowUsers;
use App\Http\Controllers\Admin\TesisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowInfoUser;
use App\Http\Controllers\Site\avanceTesisController;
use App\Http\Middleware\isDirector;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Rutas publicas
Route::withoutMiddleware(['auth'])->group(function(){
    Route::controller(LoginController::class)->group(function(){
        Route::get('/login', 'showLogin')->name('login')->middleware('guest');
        Route::post('/login','login')->name('login.post');
    });
    
    Route::controller(RegisterController::class)->group(function(){
        Route::get('/register', 'showRegister')->name('register.index');
        Route::post('/register', 'register')->name('register.post');
    });
    Route::controller(ResetPwsdController::class)->group(function(){
        Route::get("/forgotPassword",'index')->name("forgotPassword");
        Route::post("/forgotPassword","resetPassword")->name("forgotPassword");
    });
});

//Rutas privadas accesibles al iniciar sesion
Route::middleware(['auth'])->group(function(){
    //Rutas para admin
    Route::prefix('/sys/admin')->middleware(['isAdmin'])->group(function(){
        Route::get('',[PanelController::class,'index'])->name('administrador');

        Route::controller(TesisController::class)->group(function(){
            Route::get('/review-historial/{id}','historialTesis')->name('tesis.historial');
            Route::get('/review-tesis','standbyIndex')->name('tesis.review');
            Route::get('/current-tesis','showCurrentlyTesis')->name('tesis.admin');
            Route::get('/view-avance/{id_tesis}','showAvanceAdmin')->name('tesis.avance.admin');
            Route::post('/review-tesis/updateState/{id}','updateState')->name('tesis.review.update');
            Route::post('tesis/asignar-comite/{id}','asignarComite')->name('tesis.comite.attach');
            Route::post("/formulary","create")->name("tesis.create");

        });
       
        Route::controller(UnidadController::class)->prefix("/unidades")->group(function(){
            Route::get('/', 'index')->name('unidades.index'); // Lista todas las unidades
            Route::get('create', 'store')->name('unidades.create'); // Muestra el formulario para crear una nueva unidad
            Route::post('/', 'create')->name('unidades.store'); // Guarda una nueva unidad
            Route::get('{id}/edit', 'edit')->name('unidades.edit'); // Muestra el formulario para editar una unidad existente
            Route::put('{id}', 'update')->name('unidades.update'); // Actualiza una unidad existente
            Route::delete('{id}', 'delete')->name('unidades.destroy'); // Elimina una unidad existente
        });
        
        Route::controller(ProgramaController::class)->prefix('/programas')->group(function(){
            Route::get("index/{id?}","index")->name('programas.index');//devuelve vista del index
            Route::get('create', 'store')->name('programas.store');//devuelve vista del create
            Route::post('/', 'create')->name('programas.create');//Metodo para subir los datos del formulario
        
            Route::get('{id}/edit', 'edit')->name('programas.edit');//devuelve vista del update
            Route::put('{id?}', 'update')->name('programas.update');//metodo para editar el programa seleccionado
            Route::delete('{id}', 'delete')->name('programas.destroy');//metodo que elimina el programa
        });
        
        Route::controller(ShowUsers::class)->prefix('/users')->group(function(){
            Route::get("/","index")->name("users.index");
            Route::get("edit/{id}","edit")->name("users.edit");
            Route::put("{id}","update")->name("users.update");
            Route::delete("{id}","delete")->name("users.delete");
        });
        
        Route::controller(ComiteController::class)->prefix("/comites")->group(function(){
            Route::get("/","index")->name("comites.index");
            Route::get("/comite-members/{id}","saveMembers")->name("comites.members");
            Route::get("/edit-comite/{id}","edit")->name("comites.edit");

            Route::post("/comite-members/save","registerMembers")->name("comites.save.members");
            // Route::get("/create/{id?}","store")->name("comites.store");
            Route::post('edit-tesis','editButton')->name('comites.edit.button');
            Route::post("/create/registro","create")->name("comites.create");
            Route::post('/{id}/copy','cloneComite')->name('comites.clone');
        
            Route::post('/{id}','destroy')->name('comites.destroy');
            Route::put('/edit/{id}', "update")->name("comites.update");
        
        });
    });
    //desloggear al usuario, esta qui porque esta ruta debe ser accesible solo si se registro el usuario
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    //Ruta temporal que quiza se use para personalizar roles
    Route::controller(RolController::class)->group(function(){
        Route::get("admin/roles","index")->name("roles.index");
        Route::get('admin/roles/store/{id}/{docentes}','storeRoles')->name('roles.store');
        Route::post('admin/roles/create','createRol')->name('roles.create');
        Route::post("/admin/roles/define-roles/{id}","definirRolUsuarios")->name('comites.saveRoles');
        Route::post("/admin/roles/update-roles","updateRoles")->name('roles.update');
    });
    //DOCENTES
    //CREAR MIDDLEWARE PARA QUE ESTO SOLO SEA PARA DIRECTORES DE TESIS
    Route::controller(TesisController::class)->prefix("/tesis")->middleware(['isDirector'])->group(function(){
        Route::get("/","index")->name("tesis.index"); 
        Route::get("/formulary/{id?}","viewRequerimientos")->name("tesis.requerimientos");
        //Route::get("/formulary/requerimientos/{id?}")
        //Route::post("/formulary","create")->name("tesis.create");
        Route::post("/formulary/delete/{id}","delete")->name("tesis.delete");
        Route::post("formulary/requerimientos/{id}","createRequerimientos")->name("tesis.create.requerimientos");
    });
    
    Route::controller(HomeController::class)->prefix("/home")->middleware(['isEstudiante'])->group(function(){
        Route::get("/","index")->name("home");
        Route::get("/comites","showComite")->name("home.comite");
    });

    Route::controller(avanceTesisController::class)->prefix("/requerimiento")->group(function(){
        Route::get("/{id}","showAvance")->name("avance.index");
        Route::post("/create/{id}","createAvance")->name("avance.create");
        Route::post("/comentario","comentarioAvance")->name("comentario.create");
        Route::post("/estado","updateEstadoAvance")->name("avance.estado.update");
    });
    
    Route::controller(ShowInfoUser::class)->prefix('myInfo')->group(function(){
        Route::get('/tesis','showTesis')->name('info.tesis');
        Route::get('/comites','showComites')->name('info.comites');
        Route::get('/unidad','showUnidad')->name('info.unidad');
    });

    
});

Route::controller();
Route::get("home/index",function(){
    return Auth::user()->programas;
});

Route::controller(PlanDeTrabajoController::class)->prefix('plan-trabajo')->group(function(){
    Route::get('/historial/{id}','historial')->name('plan.historial');
    Route::get('/{id}','index')->name('plan.index');
    Route::get('/{id_comite}/plan-edit/{id_plan}','edit')->name('plan.edit');
    Route::get('/print-plan/{id}','exportarPDF')->name('plan.print');
    Route::post('/create','create')->name('plan.create');
    Route::post('/plan-history/{id_plan}','update')->name('plan.update');
});