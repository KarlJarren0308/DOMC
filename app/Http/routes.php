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
Route::get('panel', array('as' => 'panel.getIndex', 'uses' => 'PanelController@getIndex'));
Route::get('panel/manage/{what}', array('as' => 'panel.getManage', 'uses' => 'PanelController@getManage'));
Route::get('panel/manage/{what}/add', array('as' => 'panel.getAdd', 'uses' => 'PanelController@getAdd'));
Route::get('panel/manage/{what}/edit/{id}', array('as' => 'panel.getEdit', 'uses' => 'PanelController@getEdit'));
Route::get('panel/manage/{what}/delete/{id}', array('as' => 'panel.getDelete', 'uses' => 'PanelController@getDelete'));

Route::post('login', array('as' => 'main.postLogin', 'uses' => 'MainController@postLogin'));