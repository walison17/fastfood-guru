<?php

//auth routes
$app->get('/auth/login', 'AuthController:index')->setName('auth.index');
$app->post('/auth/login', 'AuthController:login')->setName('auth.login');
$app->get('/auth/logout', 'AuthController:logout')->setName('auth.logout');

$app->get('/cadastro', 'RegistrationController:showForm')->setName('reg.showForm');
$app->post('/cadastrar', 'RegistrationController:register')->setName('reg.register');

$app->get('/edit', 'UserPhotoController:index')->setName('user.edit');
$app->post('/upload-photo', 'UserPhotoController:store')->setName('user.uploadPhoto');

//get routes
$app->get('/', '\App\Controllers\PagesController:index')->setName('home');

$app->get('/storage/{filename}', 'App\Controller\StorageController:show')->setName('storage.show');