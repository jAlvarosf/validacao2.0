<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|Aqui é onde você pode registrar rotas da web para seu aplicativo.
|Essas rotas são carregadas pelo RouteServiceProvider dentro de um grupo que
|contém o grupo de middleware "web". 
|Agora crie algo ótimo!
|
*/

Route::resource('livros', 'LivroController');

Auth::routes();

Route::get('/home', 'LivroController@index')->name('Home');
Route::get('/', 'LivroController@index');