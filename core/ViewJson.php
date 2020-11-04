<?php

namespace Core;

class ViewJson implements ViewInterface
{
    public function __construct(string $templatePath)
    {
        if (!$templatePath) {
            throw new \InvalidArgumentException('Не установлен TEMPLATE_PATH');
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