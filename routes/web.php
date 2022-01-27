<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$controllersPath = "App\Http\Controllers";
Route::get('/', function () {
    return view('welcome',[
        'verse' => \App\Http\Controllers\Controller::getVerses(true),
    ]);
})->name('index');
Route::get('/cadastrar/{email?}', "$controllersPath\UserController@create")->name('register');
Route::post('/usuario/cadastrar', "$controllersPath\UserController@store")->name('user.store');

Route::get('/esqueceu-a-senha', function () {
    return view('welcome',[
        'verse' => \App\Http\Controllers\Controller::getVerses(true),
        'formActive' => 'forgot-password'
    ]);
})->name('forgot-password');

Route::name('redefine-password.')->group(function() use ($controllersPath){
    Route::post('/esqueceu-a-senha/enviar',
        "$controllersPath\UserController@forgotPassword"
    )->name('send');
    Route::get('/esqueceu-a-senha/nova-senha/{email}&{token}',
        "$controllersPath\UserController@redefinePassword"
    )->name('index');
    Route::post('/esqueceu-a-senha/salvar',
        "$controllersPath\UserController@storePassword"
    )->name('store');
});

Route::post('/login', "$controllersPath\Auth\LoginController@authenticate")->name('login');
Route::get('/login', "$controllersPath\Auth\LoginController@login")->name('login');
Route::post('/login-email', "$controllersPath\Auth\LoginController@authenticateEmail")->name('login.email');
Route::get('/logout', "$controllersPath\Auth\LoginController@logout")->name('logout');

Route::get('/politicas-de-privacidade', "$controllersPath\Controller@policy")->name('policy');
Route::get('/termos-de-servico', "$controllersPath\Controller@terms")->name('terms');

Route::middleware(['auth'])->group(function () use ($controllersPath) {
    Route::get('/home',"$controllersPath\HomeController@index")->name('home');

    Route::name('ministry.')->group(function() use ($controllersPath) {
        Route::get('/ministerio/detalhes/{slug}', "$controllersPath\MinistryController@index")->name('index');
        Route::get('/ministerio/selecionar/{id}', "$controllersPath\MinistryController@select")->name('select');
        Route::get('/ministerio/novo', "$controllersPath\MinistryController@create")->name('create');
        Route::post('/ministerio/salvar', "$controllersPath\MinistryController@store")->name('store');
        Route::get('/ministerio/outros', "$controllersPath\MinistryController@outhers")->name('outhers');
        Route::get('/ministerio/ingressar/{slug}', "$controllersPath\MinistryController@bind")->name('bind');
    });

    Route::name('user.')->group(function() use ($controllersPath) {
        Route::get('/perfil/{email?}', "$controllersPath\UserController@profile")->name('profile');
        Route::post('/usuário/atualizar/disponibilidade', "$controllersPath\UserController@updateAvailability")->name('update.availability');
    });

    Route::name('ability.')->group(function() use ($controllersPath) {
        Route::post('/habilidade/vincular', "$controllersPath\AbilityController@bind")->name('bind');
    });

    Route::name('user_ministry.')->group(function() use ($controllersPath) {
        Route::post('ministerio/usuario/editar', "$controllersPath\UserMinistryController@update")->name('update');
    });

    Route::name('praise.')->group(function() use ($controllersPath) {
        Route::get('/louvores', "$controllersPath\PraiseController@index")->name('index');
        Route::get('/louvores/favoritos', "$controllersPath\PraiseController@favorite")->name('favorite');
        Route::post('/louvores/favoritos/alternar', "$controllersPath\PraiseController@toggleFavorite")->name('favorite.toggle');
        Route::get('/louvores/novo/{import?}', "$controllersPath\PraiseController@create")->name('create');
        Route::post('/louvores/salvar', "$controllersPath\PraiseController@store")->name('store');
    });

    Route::name('scale.')->group(function() use ($controllersPath) {
        Route::get('/escala/semanal', "$controllersPath\ScaleController@week")->name('week');
        Route::get('/escala/mesal', "$controllersPath\ScaleController@month")->name('month');
        Route::get('/escala/nova/{import?}', "$controllersPath\ScaleController@create")->name('create');
        Route::post('/escala/salvar', "$controllersPath\ScaleController@store")->name('store');
    });
});