<?php

namespace Core;

class ViewTwig implements ViewInterface
{
    private $tplPath = '';
    private $data = [];

    public function __construct(string $templatePath)
    {
        if (!$templatePath) {
            throw new \InvalidArgumentException('Не установлен TEMPLATE_PATH');
        }

        $this->tplPath = $this->setViewPath($templatePath);
    }

    public function render(string $tpl, $data = []): ?string
    {
        $extension = '.twig';
        $tplFullPath = $this->tplPath . DIRECTORY_SEPARATOR . $tpl . $extension;

        if (!file_exists($tplFullPath)) {
            header("HTTP/1.0 404 Not Found");
            echo 'Страница не найдена';
            return null;
        }

        $debug = false;

        if (DEV_MOD) {
            $debug =  true;
        }

        $loader = new \Twig\Loader\FilesystemLoader($this->tplPath);
        $twig = new \Twig\Environment(
            $loader,
            [
                'cache' => $this->tplPath . '_cache',
                'autoescape' => false,
                'auto_reload' => true,
                'debug' => $debug
            ]
        );

        $twig->addExtension(new \Twig\Extension\DebugExtension());

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