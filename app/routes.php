<?php

//auth routes
$app->get('/auth/login', 'AuthController:showForm')->setName('auth.showForm')->add(new \App\Middlewares\GuestMiddleware);
$app->get('/auth/logout', 'AuthController:logout')->setName('auth.logout')->add(new \App\Middlewares\AuthMiddleware);
$app->post('/auth/login', 'AuthController:login')->setName('auth.login');

$app->get('/cadastro', 'RegistrationController:showForm')->setName('reg.showForm')->add(new \App\Middlewares\GuestMiddleware);
$app->post('/cadastrar', 'RegistrationController:register')->setName('reg.register');


$app->get('/', '\App\Controllers\PagesController:index')->setName('home');
$app->get('/empresas', '\App\Controllers\PagesController:empresas')->setName('empresas');
$app->get('/empresa/{empresa}', '\App\Controllers\PagesController:empresa')->setName('empresa');
$app->get('/avaliacao', '\App\Controllers\PagesController:avaliacao')->setName('avaliacao');
$app->get('/busca', '\App\Controllers\BuscaController:index')->setName('busca.index');

$app->group('/', function () {
    $this->get('profile', '\App\Controllers\PagesController:profile')->setName('profile');
    $this->post('upload-photo', 'UserPhotoController:store')->setName('user.uploadPhoto');
    $this->get('edit', 'UserPhotoController:index')->setName('user.edit');
    $this->post('atualizar-perfil', 'UserController:update')->setName('user.update');
})->add(new \App\Middlewares\AuthMiddleware);

$app->get('/storage/{filename}', 'App\Controllers\StorageController:show')->setName('storage.show');