<?php

namespace app\core;

abstract class DBModel extends Model
{
    abstract public function tableName():string;
    abstract public function attributes():array;
    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr",$this->attributes());
        $stmt = self::prepare("INSERT INTO $tableName (".implode(",",$attributes).") VALUES (".implode(",",$params).") " );
        foreach ($this->attributes() as $attributeName){
            $stmt->bindParam(":$attributeName",$this->{$attributeName});
        }

        $stmt->execute();
        return true;
    }
    abstract  public static function primaryKey():string;
    abstract public function displayName():string;
}