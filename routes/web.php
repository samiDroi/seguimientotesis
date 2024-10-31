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
Route::controller(LoginController::class)->group(function(){
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login','login')->name('login');
});

Route::controller(RegisterController::class)->group(function(){
    Route::get('/register', 'showRegister');
    Route::post('/register', 'register')->name('register');
});
Route::controller(ResetPwsdController::class)->group(function(){
    Route::get("/forgotPassword",'index')->name("forgotPassword");
    Route::post("/forgotPassword","resetPassword")->name("forgotPassword");
});
    Route::controller(UnidadController::class)->prefix("/unidades")->group(function(){
        Route::get('', 'index')->name('unidades.index'); // Lista todas las unidades
        Route::get('create', 'store')->name('unidades.create'); // Muestra el formulario para crear una nueva unidad
        Route::post('', 'create')->name('unidades.store'); // Guarda una nueva unidad
        Route::get('{id}/edit', 'edit')->name('unidades.edit'); // Muestra el formulario para editar una unidad existente
        Route::put('{id}', 'update')->name('unidades.update'); // Actualiza una unidad existente
        Route::delete('{id}', 'delete')->name('unidades.destroy'); // Elimina una unidad existente
    });



Route::controller(ProgramaController::class)->group(function(){
    Route::get("/programas/index/{id}","index")->name('programas.index');//devuelve vista del index
    Route::get('/programas/create', 'store')->name('programas.store');//devuelve vista del create
    Route::post('/programas', 'create')->name('programas.create');//Metodo para subir los datos del formulario

    Route::get('/programas/{id}/edit', 'edit')->name('programas.edit');//devuelve vista del update
    Route::put('/programas/{id}', 'update')->name('programas.update');//metodo para editar el programa seleccionado
    Route::delete('/programas/{id}', 'delete')->name('programas.destroy');//metodo que elimina el programa
});

Route::controller(ShowUsers::class)->group(function(){
    Route::get("/admin/users","index")->name("users.index");
    Route::get("admin/users/edit/{id}","edit")->name("users.edit");
    Route::put("admin/users/{id}","update")->name("users.update");
    Route::delete("/users/{id}","delete")->name("users.delete");
});

Route::controller(ComiteController::class)->group(function(){
    Route::get("/admin/comites","index")->name("comites.index");
    Route::get("/admin/comites/create","store")->name("comites.store");
    Route::get("/admin/comites/{id}/edit","edit")->name("comites.edit");

    Route::post("/admin/comites/create","create")->name("comites.create");
    Route::delete('/comites/{id}','destroy')->name('comites.destroy');
    Route::put('/comites/edit/{id}', "update")->name("comites.update");

});

Route::controller(RolController::class)->group(function(){
    Route::get("admin/roles","index")->name("roles.index");
});



// Route::controller();
// Route::get("home/index",function(){
//     return Auth::user();
//     return "hola este es home";
// });
// Route::get("cerrar_sesion",function(){
//     Auth::logout();
 
//     Session::invalidate();

//     Session::regenerateToken();
//     return redirect("/login");
// });
