<?php

namespace app\core;

abstract class Model
{

    public const   RULE_REQUIRED = 'required';
    public const   RULE_MIN = 'min';
    public const   RULE_MAX = 'max';
    public const   RULE_MATCH = 'match';
    public const   RULE_EMAIL = 'email';
    public const   RULE_UNIQUE = 'unique';

    public array $errors = [];

    public function loadData($data){
        foreach ($data as $key => $value){
            if(property_exists($this,$key)){
                $this->{$key} = $value;
            }
        }
    }
    public function validate(){
        foreach ($this->rules() as $attributeName => $rules){
            $value = $this->{$attributeName};
            foreach ($rules as $rule){
                $ruleName = $rule;
                if(is_array($rule)){
                    $ruleName = $rule[0];
                }
                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorForRule($attributeName,self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attributeName,self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){

                    $this->addErrorForRule($attributeName,self::RULE_MIN,$rule);
                }
                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){

                    $this->addErrorForRule($attributeName,self::RULE_MAX,$rule);
                }
                if($ruleName === 'match' && $value != $this->{$rule['match']}){
                    $rule['match'] = $this->getLabels($rule['match']);
                    $this->addErrorForRule($attributeName,self::RULE_MATCH,$rule);
                }
                if($ruleName === 'unique'){
                   $className = new $rule['class'];
                   $tableName = $className->tableName();
                   $stmt = self::prepare("SELECT * FROM $tableName WHERE $attributeName = :attr");
                   $stmt->bindParam(":attr",$value);
                   $stmt->execute();
                   $result =  $stmt->fetchColumn();
                   if($result){
                       $this->addErrorForRule($attributeName,self::RULE_UNIQUE,['field'=>'Email']);
                   }
                }

            }
        }

        return empty($this->errors);
    }
    abstract public function rules():array;
    abstract public function labels():array;
    abstract public function getLabels(string $attr):string;

    public function addError($attributeName,$message): void
    {
        $this->errors[$attributeName][] = $message;
    }

    public function addErrorForRule($attributeName, $rule, $params = []): void
    {
        $message = $this->errorMessage()[$rule] ?? '';
        foreach ($params as $paramName => $paramValue){
            $message = str_replace("{{$paramName}}",$paramValue,$message);
        }
        $this->errors[$attributeName][] = $message;
    }

    public function errorMessage():array{
        return [
            'required' => "this field is required",
            'email' => "this field should be an Email",
            'min' => "this field shouldn't be less than {min}",
            'max' => "this field shouldn't be greater than {max}",
            'match' => "this field should match {match}",
            'unique' => "{field} field should be unique"
        ];
    }

    public function hasError($attribute):mixed{
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute):string{
        return $this->errors[$attribute][0] ?? '';
    }

    public static function prepare($sql): bool|\PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}