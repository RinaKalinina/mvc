<?php

namespace Core;

use App\Controller\User;
use App\Model\User as UserModel;

class Application
{
    private $route;
    private $controller;
    private $actionName;

    public function __construct()
    {
        $this->route = new Route();
    }

    public function run()
    {
        session_start();
        $this->addRoutes();
        $this->initController();
        $this->initAction();

        if (empty($this->controller)) {
            header("HTTP/1.0 404 Not Found");
            echo 'Страница не найдена';
            return null;
        }

        if (empty($this->actionName)) {
            header("HTTP/1.0 404 Not Found");
            echo 'Страница не найдена';
            return null;
        }

        if (TEMPLATE === 'php') {
            $view = new ViewPhp(TEMPLATE_PATH);
        }
        if (TEMPLATE === 'twig') {
            $view = new ViewTwig(TEMPLATE_PATH);
        }
        if (TEMPLATE === 'json') {
            $view = new ViewJson(TEMPLATE_PATH);
        }

        $this->controller->setView($view);
        $this->initUser();

        return $this->controller->{$this->actionName}();
    }

    private function initUser()
    {
        $id = $_SESSION['id'] ?? null;

        if ($id) {
            $user = (new UserModel)->getUserById($id);

            if ($user) {
                $this->controller->setUser($user);
            }
        }
    }

    private function addRoutes()
    {
        ///** @uses \App\Controller\User::loginAction() */
        // $this->route->addRoute('/user/go', User::class, 'login');
    }

    private function initController()
    {
        $controllerName = $this->route->getControllerName();

        if (class_exists($controllerName)) {
            $this->controller = new $controllerName();
        }
    }

    private function initAction()
    {
        $actionName = $this->route->getActionName();

        if (method_exists($this->controller, $actionName)) {
            $this->actionName = $actionName;
        }
    }
}