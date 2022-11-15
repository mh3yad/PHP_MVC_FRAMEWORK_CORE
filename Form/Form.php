<?php

namespace mh3yad\phpmvc\Form;

use mh3yad\phpmvc\Model;

class Form
{
    public static function begin($method='get',$action = '') : Form{
        echo sprintf("<form  class='g-3 needs-validation'  method='%s' action='%s' novalidate>",$method,$action);
        return new Form();
    }
    public function field(Model $model,$attribute):Field{
        return new Field($model,$attribute);
    }
    public static function end(){
        echo "</form>";
    }
}