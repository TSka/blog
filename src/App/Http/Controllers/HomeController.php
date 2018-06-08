<?php

namespace App\Http\Controllers;

use Framework\Http\HtmlResponse;

class HomeController
{
    public function index()
    {
        return new HtmlResponse($this->render('home'));
    }

    private function render($template): string
    {
        $templateFile = 'views/' . $template . '.php';
        ob_start();
        require $templateFile;
        return ob_get_clean();
    }
}