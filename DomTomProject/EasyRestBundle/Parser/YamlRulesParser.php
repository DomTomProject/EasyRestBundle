<?php

namespace DomTomProject\EasyRestBundle\Parser;

use Symfony\Component\Yaml\Yaml;
use DomTomProject\EasyRestBundle\Exception\RulesFileNotFoundException;
use DomTomProject\EasyRestBundle\Exception\RulesKeyNotFoundException;

class YamlRulesParser implements RulesParserInterface {

    /**
     *
     * @var string 
     */
    private $validationPath;

    /**
     * 
     * @param CacherProvider $provider
     * @param string $validationPath
     */
    public function __construct(string $validationPath) {
        $this->validationPath = $validationPath;
    }

    /**
     * @param string $name
     * @param string $key
     * @return array
     * @throws RulesKeyNotFoundException
     */
    public function parse(string $name, string $key): array {
        $filename = $this->getFilename($name);
        $rules = Yaml::parse($this->getFile($name));

        if (!isset($rules[$key])) {
            throw new RulesKeyNotFoundException('Key ' . $key . ' not found in ' . $name . ' rules file.');
        }

        return $this->generateRules($rules[$key]);
    }

    /**
     * 
     * @param array $rulesGroups
     */
    private function generateRules(array $rulesGroups): array {
        $rules = [];
        foreach ($rulesGroups as $key => $group) {
            $rules[$key] = $this->generateRuleForGroup($group);
        }

        $rules = $this->convertFromStringToPHP($rules);

        return $rules;
    }

    /**
     * 
     * @param array $rules
     */
    private function convertFromStringToPHP(array $rules) {

        foreach ($rules as $key => $rule) {
            $exported = var_export($rule, true);
            $exported = str_replace('\'', '', $exported);
            $exported = str_replace('\'v::', 'v::', $exported);
            $exported = str_replace(')\',', '),', $exported);
            $rules[$key] = 'v::' . $exported;
        }

        return $rules;
    }

    /**
     * 
     * @param array $group
     * @return string
     */
    private function generateRuleForGroup(array $group): string {
        $first = true;
        $generated = '';

        foreach ($group as $rules) {
            if (!$first) {
                $generated .= '->';
            }
            $first = false;

            // method with no arguments, ex: stringType
            if (!is_array($rules)) {
                $generated .= $rules . '()';
                continue;
            }

            $generated .= $this->generateFunctionWithArguments($rules);
        }

        return $generated;
    }

    /**
     * 
     * @param string $functionName
     * @param array $arguments
     */
    private function generateFunctionWithArguments(array $arguments): string {
        // get function name
        $functionName = current(array_keys($arguments));
        $function = $functionName . '(';
        $first = true;

        // build args for this functions
        foreach ($arguments[$functionName] as $argument) {
            if (!$first) {
                $function .= ', ';
            }
            $first = false;

            $function .= $this->detectAndCreateArgument($argument);
        }

        return $function . ')';
    }

    /**
     * 
     * @param array $arguments
     * @return string
     */
    private function generateArrayArgument(array $arguments): string {
        $arrayArgument = '[';
        $first = true;

        foreach ($arguments as $key => $argument) {
            if (!$first) {
                $arrayArgument .= ', ';
            }
            $first = false;

            $arrayArgument .= $this->detectAndCreateArgument($argument);
        }

        return $arrayArgument . ']';
    }

    /**
     * 
     * @param mixed $argument
     * @return string
     */
    private function detectAndCreateArgument($argument): string {
        $string = '';
        if (!is_array($argument)) {
            if (is_string($argument)) {
                $string .= '"' . $argument . '"';
                return $string;
            }
            $string .= $argument;
            return $string;
        } else {
            if ($this->isFunction($argument)) {
                $string .= 'v::' . $this->generateFunctionWithArguments($argument);
                return $string;
            }

            $string .= $this->generateArrayArgument($argument);
            return $string;
        }
        return $string;
    }

    /**
     * 
     * @param type $arguments
     * @return bool
     */
    private function isFunction($arguments): bool {
        return $this->isAssoc($arguments);
    }

    /**
     * 
     * @param array $array
     * @return bool
     */
    private function isAssoc(array $array): bool {
        if (array() === $array) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * 
     * @param string $name
     * @return string
     * @throws RulesFileNotFoundException
     */
    private function getFile(string $name): string {
        $filename = $this->getFilename($name);
        if (!file_exists($filename)) {
            throw new RulesFileNotFoundException('Validation file not found in: ' . $filename);
        }

        return file_get_contents($filename);
    }

    /**
     * @param string $name
     * @return string
     */
    private function getFilename(string $name): string {
        return $this->validationPath . '/' . $name . '.yml';
    }

}
