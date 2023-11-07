<?php

namespace iutnc\deefy\dispatch;

class Dispatcher
{
    private string $action;

    public function __construct(string $s){
        $this->action=$s;
    }
    public function run():void{

        switch ($this->action){


        }

    }
    private function renderPage(string $html):void{
        echo "<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=UTF-8>
    <title>index</title>
</head>
<body>
    $html
</body>
</html>";
    }
}