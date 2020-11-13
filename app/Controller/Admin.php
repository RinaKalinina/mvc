<?php

namespace App\Controller;

use Core\AbsController;
use App\Model\User;
use Intervention\Image\ImageManagerStatic as ImageManager;

class Admin extends AbsController
{
    public function indexAction()
    {
        if ($this->session->quest() || !$this->getUser()->isAdmin()) {
            $this->redirect('/admin/login');
        }

        return $this->view->render('Admin/index');
    }

    public function loginAction()
    {
        $errors = [];
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email && !$password) {
            $errors[] = 1;
        }

        if (empty($errors)) {
            $user = (new User)->getUserByEmail($email);

            if ($user && $user->isAdmin() && ($user->password === $user->getPasswordHash($password))) {
                $this->session->login($user->toArray());
                $this->redirect('/admin/index');
            } else {
                $errors[] = 13;
            }
        }

        if (isset($_POST['submit'])) {
            return $this->view->render('Admin/login', [
                'errorDescription' => parent::getErrorDescription(),
                'errors' => $errors
            ]);
        }

        return $this->view->render('Admin/login');
    }

    public function createAction()
    {
        if ($this->session->quest() || !$this->getUser()->isAdmin()) {
            $this->redirect('/admin/login');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $repeatedPassword = trim($_POST['repeatedPassword'] ?? '');
        $img = null;
        $errors = [];

        if (empty($name) || empty($email) || empty($password) || empty($repeatedPassword)) {
            $errors[] = 1;
        }

        if ($password && mb_strlen($password) <= 4) {
            $errors[] = 2;
        }

        if ($password && $repeatedPassword && $password !== $repeatedPassword) {
            $errors[] = 3;
        }

        if ($_FILES['userFile']['name']) {
            $result = $this->saveImg($_FILES['userFile']);

            if ($result['errors']) {
                $errors[] = $result['errors'];
            }
            $img = $result['img'];
        }

        if (empty($errors)) {
            $data = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'img' => $img
            ];

            (new User)->addNewUser($data);

            $this->redirect('/admin/update');
        }

        if (isset($_POST['submit'])) {
            return $this->view->render('Admin/create', [
                'errorDescription' => parent::getErrorDescription(),
                'errors' => $errors
            ]);
        }

        return $this->view->render('Admin/create');
    }

    public function updateAction()
    {
        if ($this->session->quest() || !$this->getUser()->isAdmin()) {
            $this->redirect('/admin/login');
        }

        $users = (new User)->getAllUsersOrderByUpdate();

        if (isset($_POST)) {
            $data = [];
            $errors = [];
            $id = (int)$_POST['id'];

            foreach ($_POST as $key => $value) {
                if (!empty($value) && $key !== 'id' && $key !== 'submit') {
                    $data[$key] = $value;
                }
            }

            if ($_FILES['userFile']['name']) {
                $result = $this->saveImg($_FILES['userFile']);

                if ($result['errors']) {
                    $errors[] = $result['errors'];
                }
                $data['img'] = $result['img'];
            }

            if (empty($errors)) {
                User::where('id', $id)->update($data);
                $users = (new User)->getAllUsersOrderByUpdate();
            }

            return $this->view->render('Admin/update', [
                'users' => $users,
                'errorDescription' => parent::getErrorDescription(),
                'errors' => $errors
            ]);
        }

        return $this->view->render('Admin/update', [
            'users' => $users
        ]);
    }

    public function saveImg($userFile)
    {
        $data = [];
        $typeJpg = 'image/jpeg';
        $typePng = 'image/png';

        if ($userFile['name']) {
            $errorsFile = [
                'UPLOAD_ERR_INI_SIZE' => 5,
                'UPLOAD_ERR_FORM_SIZE' => 6,
                'UPLOAD_ERR_PARTIAL' => 7,
                'UPLOAD_ERR_NO_FILE' => 8,
                'UPLOAD_ERR_NO_TMP_DIR' => 9,
                'UPLOAD_ERR_CANT_WRITE' => 10,
                'UPLOAD_ERR_EXTENSION' => 11
            ];

            if (isset($errorsFile[$userFile['error']])) {
                $data['errors'] = $errorsFile[$userFile['error']];
            }

            if ($userFile['type'] != $typeJpg && $userFile['type'] != $typePng) {
                $data['errors'] = 12;
            } else {
                $filePath = ROOT_DIR . DIRECTORY_SEPARATOR . 'uploads/user';
                $generateFileName = sha1(date('Y-m-d-H-i-s') . $userFile['name']);
                $originalFileName = $userFile['name'];

                if (!is_dir($filePath)) {
                    mkdir($filePath, 0755, true);
                }

                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $newFileName = $generateFileName . '.' . $extension;

                if (move_uploaded_file($userFile['tmp_name'], $filePath . '/'
                    . $newFileName)) {
                    ImageManager::make($filePath . '/' . $newFileName)
                        ->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save();

                    $data['img'] = $newFileName;
                }
            }
        }

        return $data;
    }
}