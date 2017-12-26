<?php

namespace App\Db;

use App\Users\User;
use App\Users\UsersRepositoryInterface;
use App\Core\Auth\AuthRepositoryInterface;

class UsersRepository extends Repository implements UsersRepositoryInterface, AuthRepositoryInterface
{
    public function getById($id)
    {   
        $query = "SELECT * FROM users WHERE id = :id";

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':id', $id);
            $sttm->execute();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        $rowValues = $sttm->fetch(\PDO::FETCH_ASSOC);

        if (! is_array($rowValues)) {
            return null;
        }

        return $this->hydrate($rowValues);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email)
    {
        $query = 'SELECT * FROM users WHERE email = :email LIMIT 1';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':email', $email);
        
            $sttm->execute();
        } catch (\PDOException $e) {
            //
        }
        
        $rowValues = $sttm->fetch(\PDO::FETCH_ASSOC);

        return ! empty($rowValues) ? $this->hydrate($rowValues) : null;
    }   

    public function save(User $user)
    {
        $query = 'INSERT INTO users (email, name, password, cep) VALUES (:email, :name, :password, :cep)';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':email', $user->getEmail());
            $sttm->bindValue(':name', $user->getName());
            $sttm->bindValue(':cep', $user->getCep());
            $sttm->bindValue(':password', $user->getPassword());

            $sttm->execute();
        } catch (\PDOException $e) {
        }

        $user->setId($this->connection->lastInsertId());
        
        return $user;
    }

    public function getAll()
    {
        $query = 'SELECT * FROM users';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->execute();

        } catch (\PDOException $e) {
            //
        }

        return array_map(function ($row) {
            return $this->hydrate($row);
        }, $sttm->fetchAll(\PDO::FETCH_ASSOC)); 
    }

    private function hydrate(array $values)
    {
        $user = new User;
        $user->setId($values['id']);
        $user->setEmail($values['email']);
        $user->setName($values['name']);
        $user->setCep($values['cep']);
        $user->setPassword($values['password']);

        return $user;
    }
}