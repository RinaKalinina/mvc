<?php

namespace App\Controller;

use Core\AbsController;

class Blog extends AbsController
{
    public function indexAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');
        }

        return $this->view->render('Blog/index', [
            'user' => $this->user
        ]);
    }
}