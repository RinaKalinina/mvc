<?php

namespace App\Model;

use Core\AbsModel;

class User extends AbsModel
{
    private $id;
    private $name;
    private $password;
    private $email;
    private $createdAt;

    public function __construct($data = [])
    {
        if ($data) {
            if (isset($data['password']) && empty($data['password'])) {
                throw new \InvalidArgumentException();
            }

            if (isset($data['password']) && empty($data['name'])) {
                throw new \InvalidArgumentException();
            }

            if (isset($data['password']) && empty($data['email'])) {
                throw new \InvalidArgumentException();
            }

            if (isset($data['id'])) {
                $this->setId($data['id']);
            }

            if (isset($data['created_at'])) {
                $this->setCreatedAt($data['created_at']);
            }

            $this->setName($data['name']);
            $this->setEmail($data['email']);
            $this->setPassword($data['password']);
        }
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function save()
    {
        $insert = "INSERT INTO `users`(`name`, `password`, `email`) VALUES (
            :name, :password, :email    
        )";

        $this->getDb()->exec($insert, __METHOD__, [
            ':name' => $this->name,
            ':password' => $this->getPasswordHash($this->password),
            ':email' => $this->email
        ]);

        $lastInsertId = $this->getDb()->lastInsertId();
        $this->id = $lastInsertId;

        return $lastInsertId;
    }

    public function getUserById(int $id): ?self
    {
        $select = "SELECT * FROM `users` WHERE `id` = :id";
        $user = $this->getDb()->fetchOne($select, __METHOD__, [
            ':id' => $id
        ]);

        if ($user) {
            return new self($user);
        }
    }

    public function getUserByEmail(string $email): ?self
    {
        $select = "SELECT * FROM `users` WHERE `email` = :email";

        $user = $this->getDb()->fetchOne($select, __METHOD__, [
            ':email' => $email
        ]);

        if ($user) {
            return new self($user);
        }
    }

    public function isAdmin()
    {
        if ($this->getId() === ADMIN) {
            return true;
        }
    }
}