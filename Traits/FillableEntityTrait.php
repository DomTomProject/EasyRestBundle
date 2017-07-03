<?php

namespace DomTomProject\EasyRestBundle\Traits;

use DomTomProject\EasyRestBundle\Exception\EntityMethodNotExistsException;

/**
 * Use this trait if you want to use short data setter. 
 *
 */
trait FillableEntityTrait {

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

    /**
     * 
     * @param string $field
     * @return string
     */
    private function convertFromSnakeToCamel(string $field): string {

        $fieldPartials = explode('_', $field);

        $newField = '';
        foreach ($fieldPartials as $paramPartial) {
            $newField .= ucfirst(strtolower($paramPartial));
        }

        return $newField;
    }

    /**
     * 
     * @param string $field
     * @param type $value
     * @throws EntityMethodNotExistsException
     */
    private function fillParameter(string $field, $value) {

        $fieldSetter = $this->makeSetter($field);

        if (method_exists($this, $fieldSetter)) {
            $this->$fieldSetter($value);
        } else {
            throw new EntityMethodNotExistsException('Method ' . $fieldSetter . ' not exist in class ' . get_class($this));
        }
    }

    /**
     * 
     * @param string $field
     * @return string
     */
    private function makeSetter(string $field): string {
        return 'set' . $field;
    }

}
