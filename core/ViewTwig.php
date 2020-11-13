<?php

namespace Core;

use Core\Interfaces\ViewInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

class ViewTwig implements ViewInterface
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
        $extension = '.twig';
        $tplFullPath = $this->tplPath . DIRECTORY_SEPARATOR . $tpl . $extension;

        if (!file_exists($tplFullPath)) {
            throw new \InvalidArgumentException('Template not found');
        }

        $loader = new FilesystemLoader($this->tplPath);
        $twig = new Environment(
            $loader,
            [
                'cache' => $this->tplPath . '_cache',
                'autoescape' => false,
                'auto_reload' => true,
                'debug' => DEV_MOD
            ]
        );

        if (DEV_MOD) {
            $twig->addExtension(new DebugExtension());
        }

        return $twig->render($tpl . $extension, $data);
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