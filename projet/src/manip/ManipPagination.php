<?php

namespace iutnc\touiter\manip;

class ManipPagination
{
public static function changerPagination(bool $bool){
            if($bool){
                $_SESSION['incremente']+=5;
            }else{
                $_SESSION['incremente']-=5;
            }
}
}