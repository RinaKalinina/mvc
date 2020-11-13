<?php

namespace App\Controller;

use Core\AbsController;

class Blog extends AbsController
{
    public function indexAction()
    {
        if ($this->session->quest()) {
            $this->redirect('/user/register');
        }

        return $this->view->render('Blog/index', [
            'user' => $this->getUser()
        ]);
    }
}