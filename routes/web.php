<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'panel'], function () {
    Route::get('notifications/send', 'NotificationsController@masive');
    Route::post('notifications/send', 'NotificationsController@sendmasive');
    Route::get('chollo/publish', 'CholloController@publish')->name('chollo.publish');
    Route::get('category/pool/{id}', 'CategoryController@pool')->name('category.pool');
    Route::get('category/pending/pools','CategoryController@poolPending')->name('category.poolPendings');
    Route::post('category/pending/pools/approve','CategoryController@approvePoolPending');
    Route::post('category/pool/{id}', 'CategoryController@poolSave');
    Route::get('pool/words','CategoryController@poolWords')->name('category.poolWords');
    Route::post('chollo/dismiss', 'CholloController@dismiss');
    Route::post('category/single', 'CategoryController@poolSaveSingle');
    Route::post('brand/single', 'BrandController@poolSaveSingle');
    Route::get('brand/pool/{id}', 'BrandController@pool')->name('brand.pool');
    Route::post('brand/pool/{id}', 'BrandController@poolSave');
    Route::get('ignore/pool', 'CategoryController@ignorePool');
    Route::post('ignore/pool', 'CategoryController@ignorePoolSave');
    Route::post('category/update', 'CategoryController@update');
    Route::get('recategorize/{id}', 'CategoryController@consoleCategory');
    Voyager::routes();
});
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('/', 'HomeController@index')->name('index');
Route::post('filter', 'HomeController@filter');
Route::get('seleccionar', 'AddController@choose');

Route::get('/fixproductimages', 'CategoryController@fixproductimages');
Route::get('/fixuserimages', 'CategoryController@fixuserimages');

Route::get('categorias', 'CategoryController@index');
Route::get('categoria/{slug}', 'HomeController@category');
Route::get('categoria/{slug}/populares', 'HomeController@category');
Route::get('categoria/{slug}/nuevos', 'HomeController@category');
Route::get('categoria/{slug}/comentados', 'HomeController@category');

// Marcas
Route::get('marcas', 'BrandController@index');
Route::get('marca/{slug}', 'HomeController@brand');
Route::get('marca/{slug}/populares', 'HomeController@brand');
Route::get('marca/{slug}/nuevos', 'HomeController@brand');
Route::get('marca/{slug}/comentados', 'HomeController@brand');

// Tienda
Route::get('tiendas', 'StoreController@index');
Route::get('tienda/{slug}', 'HomeController@store');
Route::get('tienda/{slug}/populares', 'HomeController@store');
Route::get('tienda/{slug}/nuevos', 'HomeController@store');
Route::get('tienda/{slug}/comentados', 'HomeController@store');

// Ofertas
Route::get('ofertas/{query}', 'AvisadorController@index2');
Route::post('api/ofertas/getMore', 'AvisadorController@getMore');
Route::post('api/amazon', 'AvisadorController@getAmazon');
Route::get('avisador', 'AvisadorController@index2');
Route::get('api/search', 'AvisadorController@index');
Route::get('api/searchAjax', 'AvisadorController@searchAjax');
Route::post('api/getRelatedkeywords', 'AvisadorController@getRelatedkeywords');

Route::get('etiquetas', 'StoreController@tags');
Route::get('codigos-descuento', 'CuponController@index');
Route::get('eventos', 'EventController@index');
Route::get('politicas', 'HomeController@politicas');
Route::get('faqs', 'HomeController@faqs');
Route::get('reglas-normas-comunidad', 'HomeController@reglas');
Route::get('politica-cookies', 'HomeController@cookies');
Route::get('politica-privacidad', 'HomeController@privacidad');
Route::get('busquedas', 'StoreController@search');
Route::get('populares', 'HomeController@populares');
Route::get('nuevos', 'HomeController@nuevos');
Route::get('comentados', 'HomeController@comentados');
Route::get('monedas', 'HomeController@monedas');
Route::get('contacto', 'AddController@contact');
Route::post('contacto', 'AddController@pcontact');
Route::get('comprar/{q}', 'HomeController@tags');
Route::get('codigos-descuento/{name}', 'CuponController@items');
Route::get('medallas/{id}/{name}', 'UserController@medallas');
Route::get('chollos/{id}/{name}', 'HomeController@user');
Route::get('seguidores/{id}/{name}', 'UserController@followers');
Route::get('siguiendo/{id}/{name}', 'UserController@following');
Route::get('estadisticas/{id}/{name}', 'UserController@statistics');
Route::get('pagina/{slug}', 'HomeController@page');
// New search on Demand
Route::post('api/pool/add', 'CategoryController@addPoolWord');
Route::post('api/user/store', 'UserController@save');
Route::post('api/user/login', 'UserController@login');
Route::post('api/chollo/vote', 'CholloController@vote');
Route::post('api/chollo/favorite', 'CholloController@favorite');
Route::get('api/chollo/popular', 'CholloController@popular');
Route::get('api/chollo/process', 'CholloController@processJob');
Route::get('api/setdisplay', 'HomeController@setdisplay');
Route::get('api/notification/get', 'NotificationsController@index');
Route::post('api/claim', 'NotificationsController@claim');
Route::get('api/gotostore/{id}', 'StoreController@goToStore');
Route::get('api/gotostore_d/{id}', 'StoreController@goToStoreD');
Route::get('api/gotoamazon', 'StoreController@goToAmazon');
Route::get('api/setfooter', 'HomeController@setfooter');
Route::post('api/chollo/getMore', 'HomeController@getMore');
Route::post('api/cupon/copied', 'CuponController@copied');
Route::post('api/cupon/liked', 'CuponController@liked');
Route::post('api/cupon/getMore', 'CuponController@getMore');
Route::get('api/store/{name}', 'StoreController@goByName');
Route::get('api/category/{id}', 'StoreController@goToCatById');
Route::get('api/redirect', 'StoreController@redirect');
Route::get('api/foro', 'UserController@loginInForumAlredyLogged');
Route::post('api/perfil', 'UserController@update');
Route::get('api/servertime', 'UserController@servertime');
Route::post('api/getcategory', 'CategoryController@getCategory');
Route::get('api/getsitemap', 'AddController@sitemap');
Route::get('api/getsidebar', 'HomeController@sidebar');
Route::post('api/getrelated', 'CholloController@related');

Route::group(['middleware' => 'auth'], function () {
    Route::get('notifications', 'NotificationsController@all');
    Route::get('favoritos', 'HomeController@favorites');
    Route::get('enviados', 'HomeController@sent');
    Route::get('mis_cupones', 'CuponController@sent');
    Route::get('alertas', 'AvisadorController@alertas');
    Route::get('settings', 'UserController@settings');
    Route::get('follows', 'UserController@follows');
    Route::get('perfil', 'UserController@perfil');
    Route::post('password', 'UserController@updatePassword');
    Route::post('email', 'UserController@updateEmail');
    Route::get('stats', 'UserController@stats');
    Route::get('nuevo/chollo', 'AddController@chollo');
    Route::get('nuevo/cupon', 'AddController@cupon');
    Route::get('nuevo/evento', 'AddController@evento');
    Route::get('editar/{id}', 'AddController@editar');
    Route::post('api/cupon/save', 'CuponController@save');
    Route::post('api/item/save', 'CholloController@save');
    Route::post('api/item/pic', 'CholloController@savePicture');
    Route::post('api/event/save', 'EventController@save');
    Route::post('api/comment/save', 'CholloController@comment');
    Route::post('api/comment/like', 'CholloController@like');
    Route::post('api/comment/dislike', 'CholloController@dislike');
    Route::post('api/keyword', 'CholloController@keyword');
    Route::get('api/notification/dismiss', 'NotificationsController@dismissAll');
    Route::get('api/notification/dismiss/{id}', 'NotificationsController@dismiss');
    Route::post('api/settings/update', 'UserController@updateSettings');
    Route::get('api/report/{id}', 'CholloController@report');
    Route::post('api/user/follow', 'UserController@follow');
    Route::post('api/user/delete', 'UserController@delete');
    Route::post('api/item/find', 'CholloController@find');
});
Route::redirect('/electronica', '/categoria/informatica-y-electronica', 301);
Route::redirect('/belleza-cuidado-personal/', '/categoria/belleza-y-salud', 301);
Route::redirect('/black-friday/', '/ofertas/black-friday', 301);
Route::redirect('/cyber-monday/', '/ofertas/cyber-monday', 301);
Route::redirect('/deporte/', '/categoria/deporte-y-aire-libre', 301);
Route::redirect('/fotografia-video/', '/categoria/fotografia-y-videocamaras', 301);
Route::redirect('/gaming/', '/categoria/gaming', 301);
Route::redirect('/telefonia/', '/categoria/smartphone-telefonia-y-wearables', 301);
Route::redirect('/ordenadores/', '/categoria/ordenadores', 301);
Route::redirect('/sonido/', '/categoria/audio', 301);
Route::redirect('/televisores/', '/categoria/televisores', 301);
Route::redirect('/chollos-gratis/', '/categoria/cosas-gratis', 301);
Route::redirect('/hogar/', '/categoria/hogar-inteligente-y-domotica', 301);
Route::redirect('/pequeno-electrodomestico/', '/categoria/pequeno-electrodomestico', 301);
Route::redirect('/juguetes-bebe/', '/categoria/juguetes-para-bebes', 301);
Route::redirect('/moda/', '/categoria/moda-calzado-y-accesorios', 301);
Route::redirect('/calzado/', '/categoria/moda-calzado-y-accesorios', 301);
Route::redirect('/equipaje/', '/categoria/equipaje', 301);
Route::redirect('/ropa/', '/categoria/moda-calzado-y-accesorios', 301);
Route::redirect('/viajes-y-ocio/', '/categoria/viajes-y-turismo', 301);
Route::redirect('/restaurantes/', '/categoria/restaurantes', 301);
Route::redirect('/viajes/', '/categoria/viajes-y-turismo', 301);
Route::redirect('/otros/', '/categoria/multicategoria', 301);
Route::redirect('/guias-de-compra/', '/categoria/multicategoria', 301);
Route::redirect('/permanentes/', '/', 301);
Route::redirect('/supermercado/', '/categoria/supermercado', 301);
Route::get('{name}', 'CholloController@item');
