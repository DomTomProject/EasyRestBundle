<?php

use DomTomProject\EasyRestBundle\Exception\BadRequestHttpJsonException;

trait RESTEntityData
{
    /**
     * @param array $data Params in snake_case
     *      format : param_name => value
     * @return $this
     * @throws BadRequestHttpJsonException
     */
    public final function setFromData(array $data) {

        foreach ($data as $field => $value) {
            $newField = $this->convertFromSnakeToCamel($field);
            $this->fillParameter($newField, $value);
        }

        return $this;
    }

    private function convertFromSnakeToCamel(string $field): string {

        $fieldPartials = explode('_', $field);

        $newField = '';
        foreach ($fieldPartials as $paramPartial) {
            $newField .= ucfirst(strtolower($paramPartial));
        }

        return $newField;
    }

    private function fillParameter(string $field, $value) {

        $fieldSetter = $this->makeSetter($field);

        if (method_exists($this, $fieldSetter)) {
            $this->$fieldSetter($value);
        } else {
            throw new Exception('Method ' . $fieldSetter . ' not exist in class ' . get_class($this));
        }
    }

    private function makeSetter(string $field): string {
        return 'set' . $field;
    }
}