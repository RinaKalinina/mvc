<?php

namespace Core;

use App\Model\User;
use Core\Interfaces\StorageInterface;
use Core\Interfaces\ViewInterface;

abstract class AbsController
{
    /** @var ViewInterface */
    protected $view;

    /** @var Auth */
    protected $session;

    protected function redirect(string $url)
    {
        return header("Location:" . $url);
    }

    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

    public function getUser(): ?User
    {
        if ($this->session->quest()) {
            return null;
        }

        $user = $this->session->user();

        $user = (new User)->getUserById($user['id']);
        if (!$user) {
            return null;
        }

        return $user;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        if ($user = $this->getUser()) {
            return $user->id;
        }

        return false;
    }

    public function setSession(StorageInterface $session): void
    {
        $this->session = new Auth($session);
    }

    public function getErrorDescription()
    {
        $errorDescription = [];
        include ROOT_DIR . DIRECTORY_SEPARATOR . 'errorDescription.php';
        return $errorDescription;
    }
}