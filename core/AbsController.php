<?php

namespace Core;

use App\Model\User;

abstract class AbsController
{
    /** @var ViewInterface*/
    protected $view;

    /** @var User */
    protected $user;

    /** @var User */
    protected $admin;

    protected function redirect(string $url)
    {
        return header("Location:" . $url);
    }

    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getErrorDescription()
    {
        $errorDescription = [];
        include ROOT_DIR . DIRECTORY_SEPARATOR . 'errorDescription.php';
        return $errorDescription;
    }
}