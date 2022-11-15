<?php

namespace mh3yad\phpmvc\Form;

use mh3yad\phpmvc\Model;

class InputField extends HTMLField
{
    public Model $model;
    public string $attribute;
    public string $type;


    public const TYPE_TEXT = 'text';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PASSWORD = 'password';

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
       parent::__construct($model,  $attribute);
    }





    public function renderInput(): string
    {
        return sprintf('<input type="%s" class="form-control %s" id="%s" name="%s" value="%s"  required>',
            $this->type,
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute},
        );
    }


    /**
     * @param string $type
     */
    public function setType(string $type): InputField
    {
        $this->type = $type;
        return $this;
    }
}