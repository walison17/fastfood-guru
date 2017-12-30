<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Domain\User\Photo;
use App\Domain\User\UsersRepositoryInterface;

class UserPhotoController
{
    private $repository;

    public function __construct(UsersRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request, Response $response)
    {
        return view('user/edit-form');
    }

    /**
     * Adiciona uma foto ao perfil do usuário
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function store(Request $request, Response $response, $args)
    {
        $user = auth()->getCurrentUser();
        $file = $request->getUploadedFiles()['photo'];

        $folder = $user->getId();
        $filename = move_file(storage_path('images/' . $folder), $file);

        $user->setPhoto(new Photo($folder . '/' . $filename));

        $this->repository->save($user);

        return json(['message' => 'Sua foto de perfil foi atualizada :)']);

    }
}