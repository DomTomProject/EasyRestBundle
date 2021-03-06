<?php

namespace DomTomProject\EasyRestBundle\Service;

use Respect\Validation\Exceptions\NestedValidationException;
use Exception;

/**
 *  Check json data by rules.
 *
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class Validation {

    private $errors = [];
    private $validFields = [];

    /**
     * Check all rules - field cannot be empty
     *
     * @param array $data
     * @param array $rules
     * @param bool $restrict
     * @return bool
     */
    public function validate(array $data, array $rules, bool $restrict = false): bool {
        $this->clearData();

        if ($restrict) {
            $this->checkIsEditable($data, $rules);
        }
        
        $this->validateFieldsByRules($data, $rules);

        return $this->isValid();
    }

    private function clearData() {
        $this->validFields = [];
    }

    /**
     * Check if fields are editable.
     *
     * @param array $data
     * @param array $rules
     * @param bool $restrict
     */
    private function checkIsEditable(array $data, array $rules) {
        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $rules)) {
                $this->errors[] = 'Field ' . $field . ' is not editable!';
            }
        }
    }

    /**
     * Valid fields by rules.
     *
     * @param array $data
     * @param array $rules
     */
    private function validateFieldsByRules(array $data, array $rules) {
        foreach ($rules as $field => $rule) {
            try {
                if (array_key_exists($field, $data)) {
                    if (!empty($rule)) {
                        $rule->setName(ucfirst($field))->assert($data[$field]);
                    }

                    $this->validFields[$field] = $data[$field];
                } else {
                    $this->errors[$field] = 'Field ' . $field . ' must be setted!';
                }
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getFullMessage();
            }
        }
    }

    public function isValid(): bool {
        return empty($this->errors);
    }

    /**
     * Check all rules - field can be empty
     *
     * @param array $data
     * @param array $rules
     * @param bool $restrict
     * @return bool
     */
    public function validateWithEmpty(array $data, array $rules, bool $restrict = false): bool {
        $this->clearData();

        foreach ($data as $field => $value) {
            try {
                if (array_key_exists($field, $rules)) {
                    if (!empty($rules[$field])) {
                        $rules[$field]->setName(ucfirst($field))->assert($value);
                    }

                    $this->validFields[$field] = $value;
                } else if ($restrict) {
                    $this->errors[$field] = 'Field ' . $field . ' is not editable!';
                }
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getFullMessage();
            }
        }

        return $this->isValid();
    }

    public function hasErrors(): bool {
        return !empty($this->errors);
    }

    public function getValidData(): array {
        return $this->validFields;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * Set fields as object if needed.
     *
     * @param $fieldName
     * @param $object
     */
    public function setFieldAsObject($fieldName, $object) {
        $this->validFields[$fieldName] = $object;
    }

}
