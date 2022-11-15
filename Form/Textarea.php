<?php

namespace app\core\Form;

use app\core\Model;

class Textarea extends HTMLField
{
    public Model $model;
    public string $attribute;


    public function renderInput(): string
    {
        return sprintf('<textarea id="%s" name="%s" class="form-control %s" rows="7" cols="25">%s</textarea>',
                        $this->attribute,
                        $this->attribute,
                        $this->model->hasError($this->attribute) ? 'is-invalid' : '',
                        $this->model->{$this->attribute}
                    );
    }
}