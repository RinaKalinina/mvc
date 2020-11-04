<?php
namespace Core;

interface ViewInterface
{
    public function __construct(string $templatePath);

    public function render(string $tpl, $data = []);

    public function __get($varName);

    public function setViewPath($viewPath);
}

