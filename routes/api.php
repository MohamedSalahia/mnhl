<?php

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

Route::middleware('auth:sanctum')->group(function () {

    //user route
    Route::get('/user', 'AuthController@user');
});