<?php

namespace app\core\Form;

use app\core\Model;

abstract class HTMLField
{

    public Model $model;
    public string $attribute;
    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {

        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString(): string
    {
        return sprintf(
            '
                    <label for="%s" class="form-label">%s</label>
                        %s
                     <div class="invalid-feedback">
                        %s
                     </div>
                 ',
            $this->attribute,
            $this->model->getLabels($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
    abstract public function renderInput():string;
}