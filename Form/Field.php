<?php

namespace mh3yad\phpmvc\Form;

use mh3yad\phpmvc\Model;

class Field
{
    public Model $model;
    public string $attribute;
    public string $type;


    public const TYPE_TEXT = 'text';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PASSWORD = 'password';
    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
    }
    public function __toString(): string
    {
      return sprintf(
          '
                    <label for="%s" class="form-label">%s</label>
                     <input type="%s" class="form-control %s" id="%s" name="%s" value="%s"  required>
                     
                     <div class="invalid-feedback">
                        %s
                     </div>
                
                 ',
                 $this->attribute,
                $this->model->getLabels($this->attribute),
                $this->type,
                $this->model->hasError($this->attribute) ? 'is-invalid' : '',
                $this->attribute,
                $this->attribute,
                $this->model->{$this->attribute},
                $this->model->getFirstError($this->attribute)
      );
    }




    /**
     * @param string $type
     */
    public function setType(string $type): Field
    {
        $this->type = $type;
        return $this;
    }
}