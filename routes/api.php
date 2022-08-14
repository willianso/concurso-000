<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(
    [], 
    function () use ($router) {
		$router->get('/pessoa_fisica', '\App\Http\Controllers\PessoaFisicaController@index');
		$router->get('/pessoa_fisica/{id}', '\App\Http\Controllers\PessoaFisicaController@show');
		$router->post('/pessoa_fisica', '\App\Http\Controllers\PessoaFisicaController@store')->name('pessoa_fisica.store');
		$router->patch('/pessoa_fisica', '\App\Http\Controllers\PessoaFisicaController@update');
		$router->patch('/pessoa_fisica/{id}', '\App\Http\Controllers\PessoaFisicaController@destroy');
		
		$router->get('/inscricao', '\App\Http\Controllers\InscricaoController@index');
		$router->get('/inscricao/{id}', '\App\Http\Controllers\InscricaoController@show');
		$router->get('/inscricao/busca_cpf/{cpf}', '\App\Http\Controllers\InscricaoController@buscaPorCpf');
		$router->post('/inscricao', '\App\Http\Controllers\InscricaoController@store')->name('inscricao.store');
		$router->patch('/inscricao', '\App\Http\Controllers\InscricaoController@update');
		$router->patch('/inscricao/{id}', '\App\Http\Controllers\InscricaoController@destroy');

		$router->get('/cidades/{estado_id?}', '\App\Http\Controllers\CidadeController@index')->name('cidades.index');
		$router->get('/estados', '\App\Http\Controllers\EstadoController@index')->name('estados.index');
    }
);