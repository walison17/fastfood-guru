<?php 

namespace App\Domain\User;

use App\Domain\User\User;

interface UsersRepositoryInterface
{
    public function getById($id);  

    public function getAll();

    public function getByEmail(string $email);

    public function save(User $user);
}