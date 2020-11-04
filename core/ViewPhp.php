<?php

namespace Core;

class ViewPhp implements ViewInterface
{
    private $tplPath = '';
    private $data = [];

    public function __construct(string $templatePath)
    {
        //TODO примать эллемментом мыссива, через конфиг TEMPLATE_PATH через параметр
        //   $this->tplPath = ROOT_DIR . DIRECTORY_SEPARATOR . 'app/View';
        if (!$templatePath) {
            throw new \InvalidArgumentException('Не установлен TEMPLATE_PATH');
        }

        $this->tplPath = $this->setViewPath($templatePath);
    }

    public function render(string $tpl, $data = []): ?string
    {
        $extension = '.phtml';
        $tplFullPath = $this->tplPath . DIRECTORY_SEPARATOR . $tpl . $extension;

        if (!file_exists($tplFullPath)) {
            header("HTTP/1.0 404 Not Found");
            echo 'Страница не найдена';
            return null;
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