<?php

namespace App\Db;

use App\Domain\User\User;
use App\Domain\User\Photo;
use App\Core\Auth\AuthRepositoryInterface;
use App\Domain\User\UsersRepositoryInterface;

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

        return $rowValues ? $this->hydrate($rowValues) : null;
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

        return $rowValues ? $this->hydrate($rowValues) : null;
    }   

    /**
     * Adiciona ou atualiza um usuário
     *
     * @param User $user
     * @return void
     */
    public function save(User $user)
    {
        $this->hasUser($user) ? $this->update($user) : $this->insert($user);
    }

    /**
     * Retorna todos os usuários
     *
     * @return Users[]
     */
    public function getAll()
    {
        $query = 'SELECT * FROM users WHERE deleted_at IS NULL';

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

    /**
     * Verifica se o usuário já existe 
     *
     * @param User $user
     * @return boolean
     */
    public function hasUser(User $user)
    {
        return ! is_null($this->getById($user->getId()));
    }

    private function insert(User $user)
    {
        $query = 'INSERT INTO users 
            (email, name, password, photo_path, username) 
            VALUES (:email, :name, :password, :photo_path, :username)';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':email', $user->getEmail());
            $sttm->bindValue(':name', $user->getName());
            $sttm->bindValue(':password', $user->getPassword());
            $sttm->bindValue(':photo_path', $user->getPhoto()->getFilename());
            $sttm->bindValue(':username', $user->getUsername());

            $sttm->execute();
        } catch (\PDOException $e) {
        }

        $user->setId($this->connection->lastInsertId());
    }

    /**
     * Atualiza um usuário
     *
     * @return void
     */
    private function update(User $user)
    {
        $query = 'UPDATE users 
            SET email = :email, 
                name = :name, 
                password = :password, 
                photo_path = :photo_path,
                updated_at = CURRENT_TIMESTAMP()
            WHERE id = :id';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':id', $user->getId());
            $sttm->bindValue(':name', $user->getName());
            $sttm->bindValue(':email', $user->getEmail());
            $sttm->bindValue(':password', $user->getPassword());
            $sttm->bindValue(':photo_path', $user->getPhoto()->getFilename());
        
            $sttm->execute();
        } catch (\PDOException $e) {
            //
        }
    }

    private function hydrate(array $values)
    {
        $user = new User;
        $user->setId($values['id']);
        $user->setEmail($values['email']);
        $user->setName($values['name']);
        $user->setCep($values['cep']);
        $user->setPassword($values['password']);
        $user->setPhoto(new Photo($values['photo_path']));
        $user->setUsername($values['username']);

        return $user;
    }
}