<?php

namespace App\Db;

use App\Domain\User\User;
use App\Domain\User\Photo;
use App\Core\Auth\AuthRepositoryInterface;
use App\Domain\User\UsersRepositoryInterface;

class UsersRepository extends Repository implements UsersRepositoryInterface, AuthRepositoryInterface
{
    public function getById($id) : ?User
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

        return $rowValues ? $this->createInstance($rowValues) : null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email) : ?User
    {
        $query = 'SELECT * FROM users WHERE email = :email';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':email', $email);
        
            $sttm->execute();
        } catch (\PDOException $e) {
            //
        }
        
        $rowValues = $sttm->fetch(\PDO::FETCH_ASSOC);

        return $rowValues ? $this->createInstance($rowValues) : null;
    }   

     /**
     * {@inheritDoc}
     *
     * @param string $username
     * @return User|null
     */
    public function getByUsername(string $username) : ?User
    {
        $query = 'SELECT * FROM users WHERE username = :username';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':username', $username);
        
            $sttm->execute();
        } catch (\PDOException $e) {
            //
        }
        
        $rowValues = $sttm->fetch(\PDO::FETCH_ASSOC);

        return $rowValues ? $this->createInstance($rowValues) : null;
    }  

    /**
     * Adiciona ou atualiza um usuário
     *
     * @param User $user
     * @return void
     */
    public function save(User $user) : void
    {
        $this->has($user) ? $this->update($user) : $this->insert($user);
    }

    /**
     * Retorna todos os usuários
     *
     * @return Users[]
     */
    public function getAll() : aray
    {
        $query = 'SELECT * FROM users WHERE deleted_at IS NULL';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->execute();

        } catch (\PDOException $e) {
            //
        }

        return array_map(function ($row) {
            return $this->createInstance($row);
        }, $sttm->fetchAll(\PDO::FETCH_ASSOC)); 
    }

    /**
     * Verifica se o usuário já existe 
     *
     * @param User $user
     * @return boolean
     */
    public function has(User $user) : bool
    {
        return ! is_null($this->getById($user->getId()));
    }

    private function insert(User $user) : void
    {
        $query = 'INSERT INTO users 
            (email, name, password, photo_path, city, state, latitude, longitude) 
            VALUES (:email, :name, :password, :photo_path, :city, :state, :latitude, :longitude)';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':email', $user->getEmail());
            $sttm->bindValue(':name', $user->getName());
            $sttm->bindValue(':password', $user->getPassword());
            $sttm->bindValue(':photo_path', $user->hasPhoto() ? $user->getPhoto() : null);
            $sttm->bindValue(':city', $user->getLocation()->getCity());
            $sttm->bindValue(':state', $user->getLocation()->getState());
            $sttm->bindValue(':latitude', $user->getLocation()->getLatitude());
            $sttm->bindValue(':longitude', $user->getLocation()->getLongitude());

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
    private function update(User $user) : void
    {
        $query = 'UPDATE users 
            SET email = :email, 
                name = :name, 
                password = :password, 
                photo_path = :photo_path,
                city = :city,
                state = :state, 
                latitude = :latitude,
                longitude = :longitude,
                updated_at = CURRENT_TIMESTAMP()
            WHERE id = :id';

        try {
            $sttm = $this->connection->prepare($query);
            $sttm->bindValue(':id', $user->getId());
            $sttm->bindValue(':name', $user->getName());
            $sttm->bindValue(':email', $user->getEmail());
            $sttm->bindValue(':password', $user->getPassword());
            $sttm->bindValue(':photo_path', $user->hasPhoto() ? $user->getPhoto() : null);
            $sttm->bindValue(':city', $user->getLocation()->getCity());
            $sttm->bindValue(':state', $user->getLocation()->getState());
            $sttm->bindValue(':latitude', $user->getLocation()->getLatitude());
            $sttm->bindValue(':longitude', $user->getLocation()->getLongitude());
        
            $sttm->execute();
        } catch (\PDOException $e) {
            //
        }
    }

    private function createInstance(array $values) : User
    {
        $user = new User;
        $user->setId($values['id'])
            ->setEmail($values['email'])
            ->setName($values['name'])
            ->setCep($values['cep'])
            ->setPassword($values['password'])
            ->setPhoto(new Photo($values['photo_path']))
            ->setUsername($values['username']);

        return $user;
    }
}