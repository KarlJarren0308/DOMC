<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', array('as' => 'main.getIndex', 'uses' => 'MainController@getIndex'));
Route::get('login', array('as' => 'main.getLogin', 'uses' => 'MainController@getLogin'));
Route::get('logout', array('as' => 'main.getLogout', 'uses' => 'MainController@getLogout'));
Route::get('opac', array('as' => 'main.getOpac', 'uses' => 'MainController@getOpac'));
Route::get('opac/reserve/{what}', array('as' => 'main.getReserve', 'uses' => 'MainController@getReserve'));
Route::get('panel', array('as' => 'panel.getIndex', 'uses' => 'PanelController@getIndex'));
Route::get('panel/loan', array('as' => 'panel.getLoan', 'uses' => 'PanelController@getLoan'));
Route::get('panel/reserved', array('as' => 'panel.getReserved', 'uses' => 'PanelController@getReserved'));
Route::get('panel/receive', array('as' => 'panel.getReceive', 'uses' => 'PanelController@getReceive'));
Route::get('panel/manage/{what}', array('as' => 'panel.getManage', 'uses' => 'PanelController@getManage'));
Route::get('panel/manage/{what}/add', array('as' => 'panel.getAdd', 'uses' => 'PanelController@getAdd'));
Route::get('panel/manage/{what}/edit/{id}', array('as' => 'panel.getEdit', 'uses' => 'PanelController@getEdit'));
Route::get('panel/manage/{what}/delete/{id}', array('as' => 'panel.getDelete', 'uses' => 'PanelController@getDelete'));

Route::post('login', array('as' => 'main.postLogin', 'uses' => 'MainController@postLogin'));
Route::post('panel/loan/{what}', array('as' => 'panel.postLoan', 'uses' => 'PanelController@postLoan'));
Route::post('panel/manage/{what}/add', array('as' => 'panel.postAdd', 'uses' => 'PanelController@postAdd'));