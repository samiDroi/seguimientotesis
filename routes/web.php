<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\UnidadAcademica;
use App\Http\Controllers\AcademicControl\UnidadController;
use App\Http\Controllers\AcademicControl\ProgramaController;
use App\Http\Controllers\Admin\ComiteController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Auth\ResetPwsdController;
use App\Http\Controllers\Admin\ShowUsers;
use App\Http\Controllers\Admin\TesisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowInfoUser;
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
        Route::get('/login', 'showLogin')->name('login');
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
        
        Route::get('',function(){
            return view('admin.index');
        })->name('administrador');

        Route::controller(UnidadController::class)->prefix("/unidades")->group(function(){
            Route::get('/', 'index')->name('unidades.index'); // Lista todas las unidades
            Route::get('create', 'store')->name('unidades.create'); // Muestra el formulario para crear una nueva unidad
            Route::post('/', 'create')->name('unidades.store'); // Guarda una nueva unidad
            Route::get('{id}/edit', 'edit')->name('unidades.edit'); // Muestra el formulario para editar una unidad existente
            Route::put('{id}', 'update')->name('unidades.update'); // Actualiza una unidad existente
            Route::delete('{id}', 'delete')->name('unidades.destroy'); // Elimina una unidad existente
        });
        
        Route::controller(ProgramaController::class)->prefix('/programas')->group(function(){
            Route::get("index/{id}","index")->name('programas.index');//devuelve vista del index
            Route::get('create', 'store')->name('programas.store');//devuelve vista del create
            Route::post('/', 'create')->name('programas.create');//Metodo para subir los datos del formulario
        
            Route::get('{id}/edit', 'edit')->name('programas.edit');//devuelve vista del update
            Route::put('{id}', 'update')->name('programas.update');//metodo para editar el programa seleccionado
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
            Route::get("/create/{id?}","store")->name("comites.store");
            
        
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
    });
    //DOCENTES
    Route::controller(TesisController::class)->prefix("/tesis")->group(function(){
        Route::get("/","index")->name("tesis.index"); 
        Route::get("/formulary/{id?}","store")->name("tesis.store");
        Route::post("/formulary","create")->name("tesis.create");
        Route::post("/formulary/delete/{id}","delete")->name("tesis.delete");
    });
    
    Route::controller(HomeController::class)->prefix("/home")->group(function(){
        Route::get("/","index")->name("home");
        //Route::post("/logout","logout")->name("logout");
        Route::get("/comites","showComite")->name("home.comite");
    });
    Route::controller(ShowInfoUser::class)->prefix('myInfo')->group(function(){
        Route::get('/tesis','showTesis')->name('info.tesis');
    });

    
});

Route::controller();
Route::get("home/index",function(){
    return Auth::user()->programas;
});

