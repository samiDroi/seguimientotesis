<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\UnidadAcademica;
use App\Http\Controllers\AcademicControl\UnidadController;
use App\Http\Controllers\AcademicControl\ProgramaController;
use App\Http\Controllers\Auth\ResetPwsdController;
use App\Http\Controllers\Users\ShowUsers;

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
Route::controller(UnidadController::class)->group(function(){
    Route::get('/unidades', 'index')->name('unidades.index'); // Lista todas las unidades
    Route::get('/unidades/create', 'store')->name('unidades.create'); // Muestra el formulario para crear una nueva unidad
    Route::post('/unidades', 'create')->name('unidades.store'); // Guarda una nueva unidad
    Route::get('/unidades/{id}/edit', 'edit')->name('unidades.edit'); // Muestra el formulario para editar una unidad existente
    Route::put('/unidades/{id}', 'update')->name('unidades.update'); // Actualiza una unidad existente
    Route::delete('/unidades/{id}', 'delete')->name('unidades.destroy'); // Elimina una unidad existente
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