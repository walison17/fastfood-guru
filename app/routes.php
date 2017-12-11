<?php

//auth routes
$app->get('/auth/login', 'AuthController:index')->setName('auth.index');
$app->post('/auth/login', 'AuthController:login')->setName('auth.login');
$app->get('/auth/logout', 'AuthController:logout')->setName('auth.logout');


//get routes
$app->get('/', '\App\Controllers\PagesController:index')->setName('home');