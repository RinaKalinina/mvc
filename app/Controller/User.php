<?php

namespace App\Controller;

use Core\AbsController;
use App\Model\User as UserModel;
use App\Model\Message as MessageModel;
use Core\Mail;
use Core\ViewTwig;

class User extends AbsController
{
    public function indexAction()
    {
        if ($this->session->quest()) {
            $this->redirect('/user/register');
        }

        parent::setView(new ViewTwig('app/ViewTwig'));

        return $this->view->render('User/index', [
            'user' => $this->getUser(),
            'errorDescription' => parent::getErrorDescription()
        ]);
    }

    public function registerAction()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $repeatedPassword = trim($_POST['repeatedPassword'] ?? '');
        $errors = [];

        $success = true;

        if (empty($name) || empty($email) || empty($password) || empty($repeatedPassword)) {
            $errors[] = 1;
            $success = false;
        }

        if ($password && mb_strlen($password) <= 4) {
            $errors[] = 2;
            $success = false;
        }

        if ($password && $repeatedPassword && $password !== $repeatedPassword) {
            $errors[] = 3;
            $success = false;
        }

        if ($success) {
            $data = [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ];

            $user = (new UserModel)->addNewUser($data);

            $this->session->login($user->toArray());
            $this->redirect('/blog');
        }

        if (isset($_POST['submit'])) {
            return $this->view->render('User/registration', [
                'errorDescription' => parent::getErrorDescription(),
                'errors' => $errors
            ]);
        }

        return $this->view->render('User/registration');
    }

    public function loginAction()
    {
        $errors = [];
        $email = trim($_POST['email'] ?? '');

        if ($email) {
            $password = trim($_POST['password'] ?? '');
            $user = (new UserModel)->getUserByEmail($email);

            if (!$user) {
                $errors[] = 13;
            } else {
                if ($user->password != $user->getPasswordHash($password)) {
                    $errors[] = 13;
                } else {
                    $this->session->login($user->toArray());
                    $this->redirect('/blog');
                }
            }
        }

        if (isset($_POST['submit'])) {
            return $this->view->render('User/registration', [
                'errorDescription' => parent::getErrorDescription(),
                'errors' => $errors
            ]);
        }

        return $this->view->render('User/registration');
    }

    public function logoutAction()
    {
        $this->session->logout();
        $this->redirect('/user/register');
    }

    public function messagesAction()
    {
        if (!isset($_GET['id'])) {
            return null;
        }

        $id = (int)$_GET['id'];
        $messages = (new MessageModel)->getJsonOfMessages($id);

        return $this->view->render('User/messages', [
            'messages' => $messages
        ]);
    }

    public function feedbackAction()
    {
        $errors = [];
        $userMessage = htmlspecialchars(trim($_POST['message'] ?? ''));

        if (isset($_POST['submit']) && !$_POST['message']) {
            $errors[] = 4;

            parent::setView(new ViewTwig('app/ViewTwig'));

            return $this->view->render('User/index', [
                'user' => $this->getUser(),
                'errorDescription' => parent::getErrorDescription(),
                'errors' => $errors
            ]);
        }

        (new Mail)->send($userMessage, 'feedback');

        $this->redirect('/blog');
    }
}