<?php

namespace iutnc\deefy\renderer;


use iutnc\deefy\touite\Touite;

class RendererTouite{

    protected $touite;

    public function __construct(Touite $touite){
        $this->touite = $touite;
    }



}