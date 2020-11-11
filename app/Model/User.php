<?php

namespace App\Model;

use Core\AbsModel;

class User extends AbsModel
{
    public function addNewUser(array $data = [])
    {
        if (empty($data)) {
            return null;
        }

        if ($data['password']) {
            $data['password'] = $this->getPasswordHash($data['password']);
        }

        $lastInsertId = User::query()->insertGetId($data);

        return User::where('id', $lastInsertId)->first();
    }

    public function getUserById(int $id): ?self
    {
        return User::where('id', $id)->first();
    }

    public function getUserByEmail(string $email): ?self
    {
        return User::where('email', $email)->first();
    }

    public function isAdmin()
    {
        if ($this->id === ADMIN) {
            return true;
        }
    }
}