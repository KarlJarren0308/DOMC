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
Route::get('about', array('as' => 'main.getAbout', 'uses' => 'MainController@getAbout'));
Route::get('login', array('as' => 'main.getLogin', 'uses' => 'MainController@getLogin'));
Route::get('logout', array('as' => 'main.getLogout', 'uses' => 'MainController@getLogout'));
Route::get('opac', array('as' => 'main.getOpac', 'uses' => 'MainController@getOpac'));
Route::get('opac/reserve/{what}', array('as' => 'main.getReserve', 'uses' => 'MainController@getReserve'));
Route::get('account_information', array('as' => 'main.getAccountInfo', 'uses' => 'MainController@getAccountInfo'));
Route::get('account_information/change_password', array('as' => 'main.getChangePassword', 'uses' => 'MainController@getChangePassword'));
Route::get('panel', array('as' => 'panel.getIndex', 'uses' => 'PanelController@getIndex'));
Route::get('panel/loan', array('as' => 'panel.getLoan', 'uses' => 'PanelController@getLoan'));
Route::get('panel/reserved', array('as' => 'panel.getReserved', 'uses' => 'PanelController@getReserved'));
Route::get('panel/receive', array('as' => 'panel.getReceive', 'uses' => 'PanelController@getReceive'));
Route::get('panel/manage/{what}', array('as' => 'panel.getManage', 'uses' => 'PanelController@getManage'));
Route::get('panel/manage/{what}/add', array('as' => 'panel.getAdd', 'uses' => 'PanelController@getAdd'));
Route::get('panel/manage/{what}/edit/{id}', array('as' => 'panel.getEdit', 'uses' => 'PanelController@getEdit'));
Route::get('panel/manage/{what}/delete/{id}', array('as' => 'panel.getDelete', 'uses' => 'PanelController@getDelete'));
Route::get('panel/manage/{what}/change_password/{id}', array('as' => 'panel.getChangePassword', 'uses' => 'PanelController@getChangePassword'));
Route::get('panel/reports', array('as' => 'panel.getReports', 'uses' => 'PanelController@getReports'));
Route::get('panel/system_configuration', array('as' => 'panel.getConfiguration', 'uses' => 'PanelController@getConfiguration'));

Route::post('login', array('as' => 'main.postLogin', 'uses' => 'MainController@postLogin'));
Route::post('account_information/change_password', array('as' => 'main.postChangePassword', 'uses' => 'MainController@postChangePassword'));
Route::post('cancel_reservation', array('as' => 'main.postCancelReservation', 'uses' => 'MainController@postCancelReservation'));
Route::post('search_opac', array('as' => 'main.postSearchOpac', 'uses' => 'MainController@postSearchOpac'));
Route::post('panel/loan/', array('as' => 'panel.postLoan', 'uses' => 'PanelController@postLoan'));
Route::post('panel/receive/', array('as' => 'panel.postReceive', 'uses' => 'PanelController@postReceive'));
Route::post('panel/test/', array('as' => 'panel.postTest', 'uses' => 'PanelController@postTest'));
Route::post('panel/manage/{what}/add', array('as' => 'panel.postAdd', 'uses' => 'PanelController@postAdd'));
Route::post('panel/manage/{what}/edit/{id}', array('as' => 'panel.postEdit', 'uses' => 'PanelController@postEdit'));
Route::post('panel/manage/{what}/change_password/{id}', array('as' => 'panel.postChangePassword', 'uses' => 'PanelController@postChangePassword'));
Route::post('panel/reports/{what}', array('as' => 'panel.postReports', 'uses' => 'PanelController@postReports'));
Route::post('panel/system_configuration/{what}', array('as' => 'panel.postConfiguration', 'uses' => 'PanelController@postConfiguration'));
Route::post('panel/initialize', array('as' => 'panel.postInitialize', 'uses' => 'PanelController@postInitialize'));