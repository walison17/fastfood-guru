<?php

//auth routes
$app->get('/auth/login', 'AuthController:showForm')->setName('auth.showForm')->add(new \App\Middlewares\GuestMiddleware);
$app->get('/auth/logout', 'AuthController:logout')->setName('auth.logout')->add(new \App\Middlewares\AuthMiddleware);
$app->post('/auth/login', 'AuthController:login')->setName('auth.login');

$app->get('/cadastro', 'RegistrationController:showForm')->setName('reg.showForm')->add(new \App\Middlewares\GuestMiddleware);
$app->post('/cadastrar', 'RegistrationController:register')->setName('reg.register');

$app->get('/edit', 'UserPhotoController:index')->setName('user.edit');
$app->post('/upload-photo', 'UserPhotoController:store')->setName('user.uploadPhoto');

//get routes
$app->get('/', '\App\Controllers\PagesController:index')->setName('home');
$app->get('/profile', '\App\Controllers\PagesController:profile')->setName('profile');

$app->get('/storage/{filename}', 'App\Controller\StorageController:show')->setName('storage.show');