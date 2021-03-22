<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function () {
    Route::post('url_images', 'ImageController@getSiteImages');
    Route::get('test_images', 'ImageController@index');
});
