<?php

namespace Core;

use Core\Interfaces\ViewInterface;

class ViewPhp implements ViewInterface
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

    public function render(string $tpl, $data = []): ?string
    {
        $extension = '.phtml';
        $tplFullPath = $this->tplPath . DIRECTORY_SEPARATOR . $tpl . $extension;

        if (!file_exists($tplFullPath)) {
            throw new \InvalidArgumentException('Template not found');
        }

        extract($data);
        ob_start();
        include $tplFullPath;
        return ob_get_clean();
    }

    public function __get($varName)
    {
        return $this->data[$varName] ?? null;
    }

    public function setViewPath($viewPath)
    {
        return ROOT_DIR . DIRECTORY_SEPARATOR . $viewPath;
    }
}