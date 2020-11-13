<?php

namespace Core;

use Core\Interfaces\ViewInterface;

class ViewJson implements ViewInterface
{
    private $tplPath = '';
    private $data = [];

    public function __construct(string $templatePath)
    {
        if (!$templatePath) {
            throw new \InvalidArgumentException('Not installed TEMPLATE_PATH');
        }

        if (!is_dir($templatePath) && !is_readable($templatePath)) {
            throw new \InvalidArgumentException("Not found dir: $templatePath or 
            check read permissions");
        }

        $this->tplPath = $this->setViewPath($templatePath);
    }

    public function render(string $tpl, $data = [])
    {
        // TODO: Implement render() method.
    }

    public function __get($varName)
    {
        // TODO: Implement __get() method.
    }

    public function setViewPath($viewPath)
    {
        return ROOT_DIR . DIRECTORY_SEPARATOR . $viewPath;
    }

}