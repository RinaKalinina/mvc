<?php

namespace App\Model;

use Core\AbsModel;

class User extends AbsModel
{
    protected $fillable = ['name', 'password', 'email', 'img'];

    public function messages()
    {
        return $this->hasMany('App\Model\Message');
    }

    public function addNewUser(array $data = [])
    {
        if (empty($data)) {
            return null;
        }

        if ($data['password']) {
            $data['password'] = $this->getPasswordHash($data['password']);
        }

        return User::create($data);
    }

    public function getUserById(int $id): ?self
    {
        return User::where('id', $id)->first();
    }

    public function getUserByEmail(string $email): ?self
    {
        return User::where('email', $email)->first();
    }

    public function getAllUsersOrderByUpdate()
    {
        return User::orderBy('updated_at', 'desc')->get();
    }

    public function isAdmin()
    {
        if ($this->id === ADMIN) {
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function getImg(): ?string
    {
        return $this->img ? '/uploads/user/' . $this->img : null;
    }
}