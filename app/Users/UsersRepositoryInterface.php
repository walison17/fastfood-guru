<?php 

namespace App\Users;

use App\Users\User;

interface UsersRepositoryInterface
{
    public function getById($id);  

    public function getAll();

    public function getByEmail(string $email);

    public function save(User $user);
}