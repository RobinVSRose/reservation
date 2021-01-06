<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'=>'admin',
    'middleware'    => config('admin.route.middleware'),
    'namespace'     => 'Ejoy\Reservation\Controllers\Admin',
], function (Router $router) {
    $router->resource('service', 'ServiceController');
    $router->resource('service_type', 'ServiceTypeController');
    $router->resource('schedule', 'ScheduleController');
    $router->resource('order', 'OrderController');

});

Route::group([
    'prefix'=>'api',
    'middleware'    => config('admin.route.middleware'),
    'namespace'=> 'Ejoy\Reservation\Controllers',
], function (Router $router) {
    $router->middleware(['login'])->group(function(Router $router){
        $router->any('product/list', 'ProductController@productList');
        $router->any('category/list', 'ProductController@categoryList');
        $router->any('product/detail', 'ProductController@productDetail');//产品详情
        $router->middleware(['user_middleware'])->group(function (Router $router) {//必须进行用户注册绑定的路由

        });
    });

});
